<?php

namespace App\Repository;

use App\Entity\Votes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Votes>
 *
 * @method Votes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Votes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Votes[]    findAll()
 * @method Votes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VotesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Votes::class);
    }

    public function add(Votes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Votes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function affichageResultats(){
        return $this->createQueryBuilder('votes')
        ->select('IDENTITY(votes.candidat)', 'count(votes.candidat)')
        ->groupBy('votes.candidat')
        ->orderBy('count(votes.candidat)', 'DESC')
        ->setMaxResults( 2 )
        ->getQuery()
        ->getResult();
    }

    public function findOneByElecteur($value): ?Votes
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.electeur = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}

// 'query_builder'  function (CandidatsRepository $candidats$repository) {
//     return $candidats$repository->createQueryBuilder('candidat')
//         ->join('practician_formation.practician', 'practician')
//         ->join('practician.user', 'user')
//         ->orderBy('user.firstName', 'ASC');

// SELECT       `candidat_id`, count(`candidat_id`) as nb
// FROM     `votes`
// GROUP BY `candidat_id`
// ORDER BY COUNT(*) DESC
// LIMIT 2;
    
//    public function affichageResultats($id){
//     return $this->createQueryBuilder('v')
//     ->select('count(v.candidat)')
//     ->where('v.candidat = :id')
//     ->setParameter(':id',$id)
//     ->getQuery()
//     ->getResult();
//    }
// select count(candidat_id), electeurs.nom from candidats inner join votes inner join electeurs on candidats.id = votes.candidat_id and electeurs.id = candidats.titulaire_id where candidats.id = 52  
             
//    /**
//     * @return Votes[] Returns an array of Votes objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Votes
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
