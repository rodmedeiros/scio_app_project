<?php

namespace App\Repository;

use App\Entity\EducationalLevel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EducationalLevel|null find($id, $lockMode = null, $lockVersion = null)
 * @method EducationalLevel|null findOneBy(array $criteria, array $orderBy = null)
 * @method EducationalLevel[]    findAll()
 * @method EducationalLevel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EducationalLevelRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EducationalLevel::class);
    }

    // /**
    //  * @return EducationalLevel[] Returns an array of EducationalLevel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EducationalLevel
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
