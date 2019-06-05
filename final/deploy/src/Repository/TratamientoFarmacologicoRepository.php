<?php

namespace App\Repository;

use App\Entity\TratamientoFarmacologico;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TratamientoFarmacologico|null find($id, $lockMode = null, $lockVersion = null)
 * @method TratamientoFarmacologico|null findOneBy(array $criteria, array $orderBy = null)
 * @method TratamientoFarmacologico[]    findAll()
 * @method TratamientoFarmacologico[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TratamientoFarmacologicoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TratamientoFarmacologico::class);
    }

    // /**
    //  * @return TratamientoFarmacologico[] Returns an array of TratamientoFarmacologico objects
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
    public function findOneBySomeField($value): ?TratamientoFarmacologico
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
