<?php

namespace App\Repository;

use App\Entity\Sortie;
use App\Service\ListFormSortie;
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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }
     public function findByFormFilter($listFormSortie){
        $campus = $listFormSortie->getCampus();
        $nom = $listFormSortie->getNom();
        dump($campus);
        dump($nom);


        $qb = $this->createQueryBuilder('s');
         (!$campus?:$qb->andWhere('s.campus = :campus')->setParameter('campus',$campus));
//         (!$nom?:$qb->andWhere($qb->expr()->like('s.nom',$nom)));


         $query = $qb->getQuery();
         return $query->getResult();





     }
    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

//
//    public function findOneBySomeField($value): ?Sortie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere(s.campus = :val)
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

}
