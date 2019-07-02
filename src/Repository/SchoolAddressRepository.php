<?php

namespace App\Repository;

use App\Entity\SchoolAddress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SchoolAddress|null find($id, $lockMode = null, $lockVersion = null)
 * @method SchoolAddress|null findOneBy(array $criteria, array $orderBy = null)
 * @method SchoolAddress[]    findAll()
 * @method SchoolAddress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SchoolAddressRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SchoolAddress::class);
    }

    // /**
    //  * @return SchollAddress[] Returns an array of SchollAddress objects
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
    public function findOneBySomeField($value): ?SchollAddress
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
