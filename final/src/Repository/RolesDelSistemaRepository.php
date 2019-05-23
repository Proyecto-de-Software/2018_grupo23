<?php

namespace App\Repository;

use App\Entity\RolesDelSistema;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RolesDelSistema|null find($id, $lockMode = null, $lockVersion = null)
 * @method RolesDelSistema|null findOneBy(array $criteria, array $orderBy = null)
 * @method RolesDelSistema[]    findAll()
 * @method RolesDelSistema[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RolesDelSistemaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RolesDelSistema::class);
    }

    // /**
    //  * @return RolesDelSistema[] Returns an array of RolesDelSistema objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RolesDelSistema
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
