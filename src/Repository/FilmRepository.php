<?php

namespace App\Repository;

use App\Entity\Film;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Film|null find($id, $lockMode = null, $lockVersion = null)
 * @method Film|null findOneBy(array $criteria, array $orderBy = null)
 * @method Film[]    findAll()
 * @method Film[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilmRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Film::class);
    }

    public function findBySubjectField($value)
    {
        return $this->createQueryBuilder('f')
            ->select('f', 'c', 's')
            ->join('f.content', 'c')
            ->join('c.subject', 's')
            ->andWhere('s.id = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneByContent($value): ?Film
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.content = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
