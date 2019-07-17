<?php

namespace App\Repository;

use App\Entity\TeachingResources;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TeachingResources|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeachingResources|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeachingResources[]    findAll()
 * @method TeachingResources[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeachingResourcesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TeachingResources::class);
    }

    // /**
    //  * @return TeachingResources[] Returns an array of TeachingResources objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TeachingResources
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
