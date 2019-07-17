<?php

namespace App\Repository;

use App\Entity\PedagogicalProject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PedagogicalProject|null find($id, $lockMode = null, $lockVersion = null)
 * @method PedagogicalProject|null findOneBy(array $criteria, array $orderBy = null)
 * @method PedagogicalProject[]    findAll()
 * @method PedagogicalProject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PedagogicalProjectRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PedagogicalProject::class);
    }

    // /**
    //  * @return PedagogicalProject[] Returns an array of PedagogicalProject objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PedagogicalProject
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
