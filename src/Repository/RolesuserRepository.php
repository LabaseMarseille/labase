<?php

namespace App\Repository;

use App\Entity\Rolesuser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Rolesuser>
 *
 * @method Rolesuser|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rolesuser|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rolesuser[]    findAll()
 * @method Rolesuser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RolesuserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rolesuser::class);
    }

//    /**
//     * @return Rolesuser[] Returns an array of Rolesuser objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Rolesuser
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
