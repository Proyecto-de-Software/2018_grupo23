<?php

namespace App\Repository;

use App\Entity\Consulta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Consulta|null find($id, $lockMode = null, $lockVersion = null)
 * @method Consulta|null findOneBy(array $criteria, array $orderBy = null)
 * @method Consulta[]    findAll()
 * @method Consulta[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConsultaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Consulta::class);
    }

    public function findByReason()
    {
      $sql = '
              SELECT mc.nombre as nombre, COUNT(consulta.motivo_id) as cant
              FROM consulta INNER JOIN motivo_consulta mc ON consulta.motivo_id=mc.id
              GROUP BY consulta.motivo_id
             ';
      return $this->getEntityManager()->getConnection()->executeQuery($sql)->fetchAll();
    }

    public function findByGenre()
    {
      $sql = '
              SELECT g.nombre as nombre, COUNT(p.genero_id) as cant
              FROM consulta INNER JOIN paciente p ON consulta.paciente_id=p.id INNER JOIN genero g ON p.genero_id=g.id
              GROUP BY p.genero_id
             ';
      return $this->getEntityManager()->getConnection()->executeQuery($sql)->fetchAll();
    }

    public function findByLocation()
    {
      $sql = '
              SELECT p.localidad_id as id, COUNT(p.localidad_id) as cant
              FROM consulta INNER JOIN paciente p ON p.id=consulta.paciente_id
              GROUP BY p.localidad_id
             ';
      return $this->getEntityManager()->getConnection()->executeQuery($sql)->fetchAll();
    }

    // /**
    //  * @return Consulta[] Returns an array of Consulta objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Consulta
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
