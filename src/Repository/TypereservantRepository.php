<?php

namespace App\Repository;

use App\Entity\Typereservant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Typereservant>
 *
 * @method Typereservant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Typereservant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Typereservant[]    findAll()
 * @method Typereservant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypereservantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Typereservant::class);
    }

//    /**
//     * @return Typereservant[] Returns an array of Typereservant objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Typereservant
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
