<?php

namespace App\Controller;

use App\Entity\Products;
use App\Entity\CountriesVat;
use App\Repository\ProductsRepository;
use App\Repository\CountriesRepository;
use App\Repository\CountriesVatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    #[Route("/products", name: "list", methods: ['GET', 'HEAD'])]
    public function list(Request $request, ProductsRepository $productsRepository, CountriesRepository $countriesRepository, CountriesVatRepository $countriesVatRepository) {
        try {
            $request = $this->transformJsonBody($request);
 
            if (!$request || !$request->get('locale')){
                throw new \Exception("No data or parameter 'locale' not found");
            }

            $data = $productsRepository->findAll();

            switch($request->get('locale')) {
                case 'all':
                    $countries = $countriesRepository->findAll();
                    $cvat = $countriesVatRepository->findAll();

                    $shorts_country = array();
                    foreach($countries as $country) {
                        $shorts_country[$country->id] = $country->short_name;
                    }

                    $vats = array();
                    foreach($cvat as $vat) {
                        $vats[$vat->product->id][] = [
                            'vat' => $vat->vat,
                            'country' => $vat->country->id
                        ];
                    }
                    foreach($data as $product) {
                        $product->locales = array();
                        foreach($vats[$product->id] as $vat) {
                            $product->locales[$shorts_country[$vat['country']]] = [
                                'vat' => $vat['vat'],
                                'price' => $product->price + $product->price * ($vat['vat'] / 100)
                            ];
                        }
                    }
                    break;
                default:
                    $countries = $countriesRepository->findBy(['short_name' => $request->get('locale')]);
                    if(!$countries || empty($countries)){
                        throw new \Exception("Localization is not available");
                    }
                    $countries = $countries[0];

                    $cvat = $countriesVatRepository->findBy(['country' => $countries->getId()]);

                    $vats = array();
                    foreach($cvat as $vat) {
                        $vats[$vat->product->id] = [
                            'vat' => $vat->vat,
                            'country' => $vat->country->id
                        ];
                    }

                    foreach($data as $product) {
                        $product->locale = $countries->getShortname();
                        $product->country = $countries->getName();
                        $product->price = round($product->price + $product->price * ($vats[$product->id]['vat'] / 100), 2);
                    }
                    break;
            }
            
            return $this->response($data);
        } catch(\Exception $e) {
            $data = [
                'status' => 422,
                'errors' => "Data no valid",
                'text' => $e->getMessage()
            ];

            return $this->response($data, 422);
        }
    }

    #[Route("/products", name: "products_add", methods: ["POST"])]
    public function addProduct(Request $request, EntityManagerInterface $entityManager, ProductsRepository $productsRepository, CountriesRepository $countriesRepository) {
        try {
            $request = $this->transformJsonBody($request);
    
            if (!$request || !$request->get('name') || !$request->get('price') || !$request->get('vat') || $request->get('vat') == ''){
                throw new \Exception();
            }

            $check_product = $productsRepository->findBy(['name' => $request->get('name')]);
            if($check_product && isset($check_product[0])) {
                throw new \Exception("Product with name " . $request->get('name') . " already exists");
            }
    
            $product = new Products();
            $product->setName($request->get('name'));
            $product->setPrice($request->get('price'));
            $product->setCurrency($request->get('currency') ?? 'USD');
            $entityManager->persist($product);
            $entityManager->flush();

            foreach($request->get('vat') as $c => $vat) {
                $country = $countriesRepository->findBy(['short_name' => $c]);
                if(!$country || empty($country)) {
                    throw new \Exception("There is no such country as " . $c);
                }

                if($vat > 20 || $vat < 1) {
                    throw new \Exception("VAT can have values from 1 to 20");
                }

                $country = $country[0];
                $country_vat = new CountriesVat;
                $country_vat->setCountry($country);
                $country_vat->setProduct($product);
                $country_vat->setVat($vat);
                $entityManager->persist($country_vat);
                $entityManager->flush();
            }
    
            $data = [
                'status' => 200,
                'success' => "Product added successfully",
                'product_id' => $product->getId()
            ];

            return $this->response($data);
    
        } catch (\Exception $e) {
            $data = [
                'status' => 422,
                'errors' => "Data no valid",
                'text' => $e->getMessage()
            ];

            return $this->response($data, 422);
        }
    }
 
    #[Route("/products/{id}", name: "product_get", methods: ["GET"])]
    public function getProduct(Request $request, ProductsRepository $productsRepository, CountriesRepository $countriesRepository, CountriesVatRepository $countriesVatRepository, $id) {
        try {
            $request = $this->transformJsonBody($request);

            if (!$request || !$request->get('locale')){
                throw new \Exception("No data or parameter 'locale' not found");
            }

            $product = $productsRepository->find($id);
    
            if (!$product) {
                $data = [
                    'status' => 404,
                    'errors' => "Product not found",
                ];

                return $this->response($data, 404);
            }

            $countries = $countriesRepository->findBy(['short_name' => $request->get('locale')]);
            if(!$countries || empty($countries)){
                throw new \Exception("Localization is not available");
            }
            $countries = $countries[0];

            $cvat = $countriesVatRepository->findBy(['country' => $countries->getId()]);

            $vats = array();
            foreach($cvat as $vat) {
                $vats[$vat->product->id] = [
                    'vat' => $vat->vat,
                    'country' => $vat->country->id
                ];
            }

            $product->locale = $countries->getShortname();
            $product->country = $countries->getName();
            $product->price = round($product->price + $product->price * ($vats[$product->id]['vat'] / 100), 2);

            return $this->response($product);
        } catch (\Exception $e) {
            $data = [
                'status' => 422,
                'errors' => "Data no valid",
                'text' => $e->getMessage()
            ];

            return $this->response($data, 422);
        }
    }
 
    #[Route("/products/{id}", name: "product_put", methods: ["PUT"])]
    public function updateProduct(Request $request, EntityManagerInterface $entityManager, CountriesRepository $countriesRepository, CountriesVatRepository $countriesVatRepository, ProductsRepository $productsRepository, $id) {
        try{
            $request = $this->transformJsonBody($request);

            if (!$request || !$request->get('locale')){
                throw new \Exception("No data or parameter 'locale' not found");
            }

            $product = $productsRepository->find($id);
 
            if (!$product){
                $data = [
                    'status' => 404,
                    'errors' => "Product not found",
                ];

                return $this->response($data, 404);
            }

            $country = $countriesRepository->findBy(['short_name' => $request->get('locale')]);
            if(!$country || empty($country)){
                throw new \Exception("Localization is not available");
            }
            $country = $country[0];

            if($request->get('name')) $product->setName($request->get('name'));
            if($request->get('price')) $product->setPrice($request->get('price'));
            if($request->get('currency')) $product->setCurrency($request->get('currency'));
            
            $entityManager->flush();

            if($request->get('vat')) {
                if($request->get('vat') < 1 || $request->get('vat') > 20) {
                    throw new \Exception("VAT can have values from 1 to 20");
                }
                $country_vat = $countriesVatRepository->findBy(['country' => $country->getId(), 'product' => $id])[0];
                $country_vat->setVat($request->get('vat'));
                $entityManager->flush();
            }
 
            $data = [
                'status' => 200,
                'success' => "Product updated successfully",
            ];

            return $this->response($data);
 
        } catch (\Exception $e) {
            $data = [
                'status' => 422,
                'errors' => "Data no valid",
                'text' => $e->getMessage()
            ];

            return $this->response($data, 422);
        }
 
    }
 
    #[Route("/products/{id}", name: "product_delete", methods: ["DELETE"])]
    public function deleteProduct(EntityManagerInterface $entityManager, ProductsRepository $productsRepository, CountriesVatRepository $countriesVatRepository, $id) {
        $product = $productsRepository->find($id);
 
        if (!$product){
            $data = [
                'status' => 404,
                'errors' => "Product not found",
            ];

            return $this->response($data, 404);
        }
 
        $entityManager->remove($product);
        $entityManager->flush();

        $cvat = $countriesVatRepository->findBy(['product' => $id]);
        foreach($cvat as $vat) {
            $entityManager->remove($vat);
            $entityManager->flush();
        }

        $data = [
            'status' => 200,
            'errors' => "Product deleted successfully",
        ];

        return $this->response($data);
    }
 
    public function response($data, $status = 200, $headers = []) {
        return new JsonResponse($data, $status, $headers);
    }
 
    protected function transformJsonBody(\Symfony\Component\HttpFoundation\Request $request) {
        $data = json_decode($request->getContent(), true);
    
        if ($data === null) {
            return $request;
        }
    
        $request->request->replace($data);
    
        return $request;
    }
}
