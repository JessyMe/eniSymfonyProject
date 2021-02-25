<?php

namespace App\Repository;


use App\Entity\Sortie;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    private $inscriptionRepository;
    public function __construct(ManagerRegistry $registry, InscriptionRepository $inscriptionRepository)
    {
        $this->inscriptionRepository = $inscriptionRepository;
        parent::__construct($registry, Sortie::class);
    }

    public function findWithoutArchive()
    {
        //conversion date du jour
        $nowmoins = date('y-m-d', strtotime('- 1 month'));
        $todayConvert = DateTime::createFromFormat("y-m-d",$nowmoins,null);
        dump($todayConvert);

        $qb = $this->createQueryBuilder('s');
        $qb->andwhere('s.datedebut > :todayConvert')->setParameter('todayConvert',$todayConvert);
        $query = $qb->getQuery();
        return $query->getResult();

    }

     public function findByFormFilter( $listFormSortie, $userLog)
     {
        $campus = $listFormSortie->getCampus();
        $nom = $listFormSortie->getNom();
        $datedebut = $listFormSortie->getdatedebut();
        $datefin = $listFormSortie->getdatefin();
        $sortieOrganisateur = $listFormSortie->getSortieOrganisateur();
        $sortieInscrit = $listFormSortie->getSortieInscrit();
        $sortieNonInscrit = $listFormSortie->getSortieNonInscrit();
        $sortiePassee = $listFormSortie->getSortiePassee();

        $qb = $this->createQueryBuilder('s');
        //sans sortie archivées
         $nowmoins = date('y-m-d', strtotime('- 1 month'));
         $todayConvert = DateTime::createFromFormat("y-m-d",$nowmoins,null);
        $qb->andwhere('s.datedebut > :todayConvert')->setParameter('todayConvert',$todayConvert);


        if($campus) $qb->andWhere('s.campus = :campus')->setParameter('campus',$campus);
        if($nom) $qb->andWhere('s.nom LIKE :nom')->setParameter('nom','%'.$nom.'%');
        if($datedebut && $datefin) $qb->andWhere('s.datedebut BETWEEN :datedebut AND :datefin')
                                        ->setParameter('datedebut',$datedebut)
                                        ->setParameter('datefin',$datefin);
        if($userLog && $sortieOrganisateur) $qb->andWhere('s.organisateur = :userLog')->setParameter('userLog',$userLog);
        if($userLog && $sortieInscrit) $qb->join('s.inscriptions','i')->andwhere('i.participant = :userLog')->setParameter('userLog',$userLog);
        if($userLog && $sortieNonInscrit){
            $listSorties = array_map(function($i){
                return $i->getSortie()->getId();
            },$this->inscriptionRepository->getSortieInscrit($userLog));
            if(count($listSorties) > 0){
                $qb->andWhere('s.id NOT IN ('.implode(',',$listSorties).')');
            }

        }
        if($sortiePassee) $qb->andwhere('s.etat = 5');
         $query = $qb->getQuery();
         return $query->getResult();





     }

//     public function findByEtat (int $etat){
//
//        $qb = $this->createQueryBuilder('s');
//        $qb->andWhere('s.etat = :etat ')
//            ->setParameter('etat', $etat);
//        $query = $qb->getQuery();
//        return $query->getResult();
//     }




}
