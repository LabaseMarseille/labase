<?php

namespace App\Repository;

use App\Entity\Parametrerecurrence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Parametrerecurrence>
 *
 * @method Parametrerecurrence|null find($id, $lockMode = null, $lockVersion = null)
 * @method Parametrerecurrence|null findOneBy(array $criteria, array $orderBy = null)
 * @method Parametrerecurrence[]    findAll()
 * @method Parametrerecurrence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParametrerecurrenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Parametrerecurrence::class);
    }

//    /**
//     * @return Parametrerecurrence[] Returns an array of Parametrerecurrence objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Parametrerecurrence
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
