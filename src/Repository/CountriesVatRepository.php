<?php

namespace App\Repository;

use App\Entity\CountriesVat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CountriesVat|null find($id, $lockMode = null, $lockVersion = null)
 * @method CountriesVat|null findOneBy(array $criteria, array $orderBy = null)
 * @method CountriesVat[]    findAll()
 * @method CountriesVat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CountriesVatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CountriesVat::class);
    }

}
