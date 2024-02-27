<?php

namespace App\Controller;

use App\Entity\Products;
use App\Entity\Countries;
use App\Entity\CountriesVat;
use App\Repository\ProductsRepository;
use App\Repository\CountriesRepository;
use App\Repository\CountriesVatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CountryController extends AbstractController
{
    #[Route("/country", name: "country_list", methods: ['GET', 'HEAD'])]
    public function list(CountriesRepository $countriesRepository) {
        $data = $countriesRepository->findAll();
        return $this->response($data);
    }

    #[Route("/country", name: "country_add", methods: ["POST"])]
    public function addCountry(Request $request, EntityManagerInterface $entityManager) {
        try {
            $request = $this->transformJsonBody($request);
    
            if (!$request || !$request->get('name') || !$request->get('short_name')){
                throw new \Exception();
            }
    
            $country = new Countries();
            $country->setName($request->get('name'));
            $country->setShortname($request->get('short_name'));
            $entityManager->persist($country);
            $entityManager->flush();
    
            $data = [
                'status' => 200,
                'success' => "Country added successfully",
                'product_id' => $country->getId()
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

    #[Route("/country/{id}", name: "country_get", methods: ["GET"])]
    public function getCountry(Request $request, CountriesRepository $countriesRepository, $id) {
        try {
            $request = $this->transformJsonBody($request);

            if (!$request){
                throw new \Exception("No data");
            }

            $country = $countriesRepository->find($id);
    
            if (!$country) {
                $data = [
                    'status' => 404,
                    'errors' => "Country not found",
                ];

                return $this->response($data, 404);
            }

            return $this->response($country);
        } catch (\Exception $e) {
            $data = [
                'status' => 422,
                'errors' => "Data no valid",
                'text' => $e->getMessage()
            ];

            return $this->response($data, 422);
        }
    }
 
    #[Route("/country/{id}", name: "country_put", methods: ["PUT"])]
    public function updateCountry(Request $request, EntityManagerInterface $entityManager, CountriesRepository $countriesRepository, $id) {
        try{
            $request = $this->transformJsonBody($request);

            if (!$request){
                throw new \Exception("No data");
            }

            $country = $countriesRepository->find($id);
 
            if (!$country){
                $data = [
                    'status' => 404,
                    'errors' => "Country not found",
                ];

                return $this->response($data, 404);
            }

            if($request->get('name')) $country->setName($request->get('name'));
            if($request->get('short_name')) $country->setShortname($request->get('short_name'));
            
            $entityManager->flush();
 
            $data = [
                'status' => 200,
                'success' => "Country updated successfully",
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
 
    #[Route("/country/{id}", name: "country_delete", methods: ["DELETE"])]
    public function deleteCountry(EntityManagerInterface $entityManager, CountriesRepository $countriesRepository, $id) {
        $country = $countriesRepository->find($id);
 
        if (!$country){
            $data = [
                'status' => 404,
                'errors' => "Country not found",
            ];

            return $this->response($data, 404);
        }
 
        $entityManager->remove($country);
        $entityManager->flush();

        $data = [
            'status' => 200,
            'errors' => "Country deleted successfully",
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
