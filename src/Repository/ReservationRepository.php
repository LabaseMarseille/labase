<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 *
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function listeProgramme($datemin, $datemax)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder('d');
        $query = $queryBuilder
            ->select('r')
            ->from('App:Reservation', 'r')
            ->innerJoin('r.collectif', 'c')
            ->where('r.cloture= :val')
            ->andWhere('r.calendrier= :valbis')
            ->andWhere('r.datedebut >= :datemin')
            ->andWhere('r.datedebut <= :datemax')
            ->orderBy('r.datedebut')
            ->getQuery();
        $result = $query->setParameter('val', false)->setParameter('valbis', true)
            ->setParameter('datemin', $datemin)->setParameter('datemax', $datemax)->getResult();

        return $result;
    }


    // renvoie dune liste de reservations
    public function listeReservation($filtre, $utilisateur, $etape)
    {
        $datejour=new \DateTime();

        if ($filtre=='aucuns'){
            $queryBuilder = $this->getEntityManager()->createQueryBuilder('d');
            $query = $queryBuilder
                ->select('r.id, r.titre, r.nbpersonne, c.nom as collectif, r.datedebut, r.datefin,
                        p.nom as priseenmain, u.nom as reservepar, e.libelle as etape, r.datecreation, r.calendrier')
                ->from('App:Reservation', 'r')
                ->innerJoin('r.collectif', 'c')
                ->innerJoin('r.reserveby', 'u')
                ->innerJoin('r.etape', 'e')
                ->leftJoin('r.priseenmainby', 'p')
                ->where('r.cloture= :val')
                ->andWhere('e.id= :etape')
                ->andWhere('r.datedebut >= :datejour')
                ->getQuery();
            $result = $query->setParameter('val', false)->setParameter('etape', $etape)->setParameter('datejour', $datejour)->getResult();
        }
        if ($filtre=='nonpriseenmain'){
            $queryBuilder = $this->getEntityManager()->createQueryBuilder('d');
            $query = $queryBuilder
                ->select('r.id, r.titre, r.nbpersonne, c.nom as collectif, r.datedebut, r.datefin,
                        p.nom as priseenmain, u.nom as reservepar, e.libelle as etape, r.datecreation, r.calendrier')
                ->from('App:Reservation', 'r')
                ->innerJoin('r.collectif', 'c')
                ->innerJoin('r.reserveby', 'u')
                ->innerJoin('r.etape', 'e')
                ->leftJoin('r.priseenmainby', 'p')
                ->where('r.cloture= :val')
                ->andWhere('r.priseenmainby is null')
                ->andWhere('e.id= :etape')
                ->andWhere('r.datedebut >= :datejour')
                ->getQuery();
            $result = $query->setParameter('val', false)->setParameter('etape', $etape)->setParameter('datejour', $datejour)->getResult();
        }
        if ($filtre=='lesmiennes'){

            $queryBuilder = $this->getEntityManager()->createQueryBuilder('d');
            $query = $queryBuilder
                ->select('r.id, r.titre, r.nbpersonne, c.nom as collectif, r.datedebut, r.datefin,
                        p.nom as priseenmain, u.nom as reservepar, e.libelle as etape, r.datecreation, r.calendrier')
                ->from('App:Reservation', 'r')
                ->innerJoin('r.collectif', 'c')
                ->innerJoin('r.reserveby', 'u')
                ->innerJoin('r.etape', 'e')
                ->leftJoin('r.priseenmainby', 'p')
                ->where('r.cloture= :val')
                ->andWhere('r.priseenmainby =:utilisateur')
                ->andWhere('e.id= :etape')
                ->andWhere('r.datedebut >= :datejour')
                ->getQuery();
            $result = $query->setParameter('val', false)->setParameter('etape', $etape)
                    ->setParameter('datejour', $datejour)->setParameter('utilisateur', $utilisateur)->getResult();
        }
        if ($filtre=='archive'){
            $queryBuilder = $this->getEntityManager()->createQueryBuilder('d');
            $query = $queryBuilder
                ->select('r.id, r.titre, r.nbpersonne, c.nom as collectif, r.datedebut, r.datefin,
                        p.nom as priseenmain, u.nom as reservepar, e.libelle as etape, r.datecreation, r.calendrier')
                ->from('App:Reservation', 'r')
                ->innerJoin('r.collectif', 'c')
                ->innerJoin('r.reserveby', 'u')
                ->innerJoin('r.etape', 'e')
                ->leftJoin('r.priseenmainby', 'p')
                ->where('r.cloture= :val')
                ->getQuery();
            $result = $query->setParameter('val', false)->setParameter('datejour', $datejour)->getResult();
        }
        if ($filtre=='annulee'){
            $queryBuilder = $this->getEntityManager()->createQueryBuilder('d');
            $query = $queryBuilder
                ->select('r.id, r.titre, r.nbpersonne, c.nom as collectif, r.datedebut, r.datefin,
                        p.nom as priseenmain, u.nom as reservepar, e.libelle as etape, r.datecreation, r.calendrier')
                ->from('App:Reservation', 'r')
                ->innerJoin('r.collectif', 'c')
                ->innerJoin('r.reserveby', 'u')
                ->innerJoin('r.etape', 'e')
                ->leftJoin('r.priseenmainby', 'p')
                ->where('r.cloture= :val')
                ->andWhere('e.id= :etape')
                ->getQuery();
            $result = $query->setParameter('val', true)->getResult();
        }
        if ($filtre=='validee'){
            $queryBuilder = $this->getEntityManager()->createQueryBuilder('d');
            $query = $queryBuilder
                ->select('r.id, r.titre, r.nbpersonne, c.nom as collectif, r.datedebut, r.datefin,
                        p.nom as priseenmain, u.nom as reservepar, e.libelle as etape, r.datecreation, r.calendrier')
                ->from('App:Reservation', 'r')
                ->innerJoin('r.collectif', 'c')
                ->innerJoin('r.reserveby', 'u')
                ->innerJoin('r.etape', 'e')
                ->leftJoin('r.priseenmainby', 'p')
                ->where('r.cloture= :valcloture')
                ->andWhere('e.id= :etape')
                ->andWhere('r.datedebut >= :datejour')
                ->getQuery();
            $result = $query->setParameter('valcloture', false)->setParameter('val', true)
                ->setParameter('etape', 4)->setParameter('datejour', $datejour)->getResult();
        }
        if ($filtre=='touteslesvalidees'){
            $queryBuilder = $this->getEntityManager()->createQueryBuilder('d');
            $query = $queryBuilder
                ->select('r.id, r.titre, r.nbpersonne, c.nom as collectif, r.datedebut, r.datefin,
                        p.nom as priseenmain, u.nom as reservepar, e.libelle as etape, r.datecreation, r.calendrier')
                ->from('App:Reservation', 'r')
                ->innerJoin('r.collectif', 'c')
                ->innerJoin('r.reserveby', 'u')
                ->innerJoin('r.etape', 'e')
                ->leftJoin('r.priseenmainby', 'p')
                ->where('r.cloture= :valcloture')
                ->andWhere('e.id= :etape')
                ->getQuery();
            $result = $query->setParameter('valcloture', false)->setParameter('val', true)
                ->setParameter('etape', 4)->setParameter('datejour', $datejour)->getResult();
        }

        return $result;
    }

    // renvoie dune liste de resa pour la comm
    public function listeCommunication($commfaite, $commannulee)
    {
        $datejour=new \DateTime();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder('d');
        $query = $queryBuilder
            ->select('r')
            ->from('App:Reservation', 'r')
            ->where('r.cloture= :val')
            ->andWhere('r.datedebut >= :datejour')
            ->andWhere('r.commfaite= :faite')
            ->andWhere('r.commannulee= :annulee')
            ->andWhere('r.comm= :comm')
            ->getQuery();
        $result = $query->setParameter('val', false)->setParameter('comm', true)->setParameter('datejour', $datejour)
            ->setParameter('faite', $commfaite)->setParameter('annulee', $commannulee)
            ->getResult();
        return $result;
    }

    // renvoie dune liste de reservartions
    public function listeReservationByUser($utilisateur, $type)
    {
        $datejour=new \DateTime();

        if ($type=='encours'){
            $queryBuilder = $this->getEntityManager()->createQueryBuilder('d');
            $query = $queryBuilder
                ->select('r.id, r.titre, r.nbpersonne, c.nom as collectif, r.datedebut, r.datefin,
                        p.nom as priseenmain, u.nom as reservepar, e.libelle as etape, r.datecreation')
                ->from('App:Reservation', 'r')
                ->innerJoin('r.collectif', 'c')
                ->innerJoin('r.reserveby', 'u')
                ->innerJoin('r.etape', 'e')
                ->leftJoin('r.priseenmainby', 'p')
                ->where('r.cloture= :val')
                ->andWhere('u.email= :utilisateur')
                ->andWhere('r.recurrent= :val')
                ->andWhere('r.datedebut >= :datejour')
                ->getQuery();
            $result = $query->setParameter('val', 'false')->setParameter('utilisateur', $utilisateur)->setParameter('datejour', $datejour)->getResult();
        }else{
            $queryBuilder = $this->getEntityManager()->createQueryBuilder('d');
            $query = $queryBuilder
                ->select('r.id, r.titre, r.nbpersonne, c.nom as collectif, r.datedebut, r.datefin,
                        p.nom as priseenmain, u.nom as reservepar, e.libelle as etape, r.datecreation')
                ->from('App:Reservation', 'r')
                ->innerJoin('r.collectif', 'c')
                ->innerJoin('r.reserveby', 'u')

                ->innerJoin('r.etape', 'e')
                ->leftJoin('r.priseenmainby', 'p')
                ->where('r.cloture= :val')
                ->andWhere('u.email= :utilisateur')
                ->andWhere('r.recurrent= :val')
                ->andWhere('r.datedebut < :datejour')
                ->getQuery();
            $result = $query->setParameter('val', 'false')->setParameter('utilisateur', $utilisateur)->setParameter('datejour', $datejour)->getResult();
        }
        return $result;
    }


//    /**
//     * @return Reservation[] Returns an array of Reservation objects
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

//    public function findOneBySomeField($value): ?Reservation
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
