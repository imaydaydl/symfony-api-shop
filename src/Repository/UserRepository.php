<?php

namespace App\Repository;

use App\Entity\Users;
use App\Entity\UserRole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
    }

    public function findAllOrderedByStatus()
    {
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

    public function findByUARole()
    {
        $qb = $this->createQueryBuilder('u');
        $qb->select('u.id, u.name')
            ->join('u.role', 'ur')
            ->where('u.status = 1')
            ->andWhere("ur.techName = 'ROLE_ADMIN'");

        return $qb->getQuery()->getResult();
    }

    public function findByFUARole()
    {
        $qb = $this->createQueryBuilder('u');
        $qb->select("u.id, CONCAT(u.surname, ' ', u.name) as name")
            ->join('u.role', 'ur')
            ->where('u.status = 1')
            ->andWhere("ur.techName = 'ROLE_ADMIN'");

        return $qb->getQuery()->getResult();
    }

    public function findByURole()
    {
        $qb = $this->createQueryBuilder('u');
        $qb->select("u.id, CONCAT(u.surname, ' ', u.name) as name")
            ->join('u.role', 'ur')
            ->where('u.status = 1')
            ->andWhere("ur.techName = 'ROLE_USER'");

        return $qb->getQuery()->getResult();
    }

}
