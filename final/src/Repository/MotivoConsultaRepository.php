<?php

namespace App\Repository;

use App\Entity\MotivoConsulta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MotivoConsulta|null find($id, $lockMode = null, $lockVersion = null)
 * @method MotivoConsulta|null findOneBy(array $criteria, array $orderBy = null)
 * @method MotivoConsulta[]    findAll()
 * @method MotivoConsulta[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MotivoConsultaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MotivoConsulta::class);
    }

    // /**
    //  * @return MotivoConsulta[] Returns an array of MotivoConsulta objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MotivoConsulta
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
