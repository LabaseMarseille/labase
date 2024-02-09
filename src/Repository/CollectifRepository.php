<?php

namespace App\Repository;

use App\Entity\Collectif;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Collectif>
 *
 * @method Collectif|null find($id, $lockMode = null, $lockVersion = null)
 * @method Collectif|null findOneBy(array $criteria, array $orderBy = null)
 * @method Collectif[]    findAll()
 * @method Collectif[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CollectifRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Collectif::class);
    }

    // renvoie dune liste d'collectifs
    public function listeCollectif()
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder('d');
        $query = $queryBuilder
            ->select('c.id, c.nom, c.abreviation, c.mail, c.telephone, c.siret, c.codepostal, c.datecreation')
            ->from('App:Collectif', 'c')
            ->where('c.cloture= :val')
            ->getQuery();
        $result = $query->setParameter('val', 'false')->getResult();
        return $result;
    }


//    /**
//     * @return Collectif[] Returns an array of Collectif objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Collectif
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
