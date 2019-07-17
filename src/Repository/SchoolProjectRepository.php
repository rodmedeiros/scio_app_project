<?php

namespace App\Repository;

use App\Entity\SchoolProject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SchoolProject|null find($id, $lockMode = null, $lockVersion = null)
 * @method SchoolProject|null findOneBy(array $criteria, array $orderBy = null)
 * @method SchoolProject[]    findAll()
 * @method SchoolProject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SchoolProjectRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SchoolProject::class);
    }

    // /**
    //  * @return SchoolProject[] Returns an array of SchoolProject objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SchoolProject
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
