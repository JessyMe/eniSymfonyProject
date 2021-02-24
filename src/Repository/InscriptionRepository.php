<?php

namespace App\Repository;

use App\Entity\Inscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Inscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method Inscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method Inscription[]    findAll()
 * @method Inscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inscription::class);
    }
    public function getSortieInscrit($user){

        $qb = $this->createQueryBuilder('i');

        $qb->where('i.participant = :user')->setParameter('user',$user);
        $query = $qb->getQuery();
        return $query->getResult();
    }

    public function findOneByParticipantAndSortie($sortie, $user)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.sortie = :sortie')->setParameter('sortie', $sortie)
            ->andWhere('i.participant = :user')->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

//    public function findAllBySortie($sortie)
//    {
//        return $this->createQueryBuilder('i')
//            ->where('i.sortie = :sortie')->setParameter('sortie', $sortie)
//            ->getQuery()
//            ->getResult();
//    }


    // /**
    //  * @return Inscription[] Returns an array of Inscription objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Inscription
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
