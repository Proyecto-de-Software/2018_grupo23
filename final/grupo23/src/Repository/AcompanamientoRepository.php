<?php

namespace App\Repository;

use App\Entity\Acompanamiento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Acompanamiento|null find($id, $lockMode = null, $lockVersion = null)
 * @method Acompanamiento|null findOneBy(array $criteria, array $orderBy = null)
 * @method Acompanamiento[]    findAll()
 * @method Acompanamiento[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AcompanamientoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Acompanamiento::class);
    }

    // /**
    //  * @return Acompanamiento[] Returns an array of Acompanamiento objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Acompanamiento
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
