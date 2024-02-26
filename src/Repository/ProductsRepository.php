<?php

namespace App\Repository;

use App\Entity\Products;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Products|null find($id, $lockMode = null, $lockVersion = null)
 * @method Products|null findOneBy(array $criteria, array $orderBy = null)
 * @method Products[]    findAll()
 * @method Products[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductsRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Products::class);
    }

    public function findAllByLocale() {
        $qb = $this->createQueryBuilder('u');
        $qb->select(
            'u.id,
					ur.id as role,
					ur.roleName as role_name,
					u.name,
					u.surname,
					u.email,
					u.password,
					u.status'
        )
        ->leftJoin('u.role','ur')
        ->orderBy('u.status','DESC');

        return $qb->getQuery()->getResult();
    }

    public function findByUARole() {
        $qb = $this->createQueryBuilder('u');
        $qb->select('u.id, u.name')
            ->join('u.role', 'ur')
            ->where('u.status = 1')
            ->andWhere("ur.techName = 'ROLE_ADMIN'");

        return $qb->getQuery()->getResult();
    }

    public function findByFUARole() {
        $qb = $this->createQueryBuilder('u');
        $qb->select("u.id, CONCAT(u.surname, ' ', u.name) as name")
            ->join('u.role', 'ur')
            ->where('u.status = 1')
            ->andWhere("ur.techName = 'ROLE_ADMIN'");

        return $qb->getQuery()->getResult();
    }

    public function findByURole() {
        $qb = $this->createQueryBuilder('u');
        $qb->select("u.id, CONCAT(u.surname, ' ', u.name) as name")
            ->join('u.role', 'ur')
            ->where('u.status = 1')
            ->andWhere("ur.techName = 'ROLE_USER'");

        return $qb->getQuery()->getResult();
    }
}
