<?php

namespace App\Repository;

use App\Entity\Slides;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Slides|null find($id, $lockMode = null, $lockVersion = null)
 * @method Slides|null findOneBy(array $criteria, array $orderBy = null)
 * @method Slides[]    findAll()
 * @method Slides[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SlidesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Slides::class);
    }

    // /**
    //  * @return Slides[] Returns an array of Slides objects
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
    public function findOneBySomeField($value): ?Slides
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
