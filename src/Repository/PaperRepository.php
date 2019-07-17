<?php

namespace App\Repository;

use App\Entity\Paper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Paper|null find($id, $lockMode = null, $lockVersion = null)
 * @method Paper|null findOneBy(array $criteria, array $orderBy = null)
 * @method Paper[]    findAll()
 * @method Paper[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaperRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Paper::class);
    }

    // /**
    //  * @return Paper[] Returns an array of Paper objects
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
    public function findOneBySomeField($value): ?Paper
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
