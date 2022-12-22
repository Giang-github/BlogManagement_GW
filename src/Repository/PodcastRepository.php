<?php

namespace App\Repository;

use App\Entity\Podcast;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Podcast>
 *
 * @method Podcast|null find($id, $lockMode = null, $lockVersion = null)
 * @method Podcast|null findOneBy(array $criteria, array $orderBy = null)
 * @method Podcast[]    findAll()
 * @method Podcast[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PodcastRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Podcast::class);
    }

    public function save(Podcast $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Podcast $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function searchPodcast($title) 
{
    return $this->createQueryBuilder('podcast')
        ->andWhere('podcast.title LIKE :n')
        ->setParameter('n', '%' . $title . '%')
        ->getQuery()
        ->getResult()
    ;
}

public function sortPodcastByIdDesc()
{
    return $this->createQueryBuilder('podcast')
        ->orderBy('podcast.id', 'DESC')
        ->getQuery()
        ->getResult();

}

public function sortPodcastByIdAsc()
{
    return $this->createQueryBuilder('podcast')
        ->orderBy('podcast.id', 'Asc')
        ->getQuery()
        ->getResult();
        
}

public function sortPodcastNameAsc()
{
    return $this->createQueryBuilder('podcast')
        ->orderBy('podcast.content', 'ASC')
        ->getQuery()
        ->getResult();
}


public function sortPodcastNameDesc()
{
    return $this->createQueryBuilder('podcast')
        ->orderBy('podcast.content', 'DESC')
        ->getQuery()
        ->getResult();
}

//    /**
//     * @return Podcast[] Returns an array of Podcast objects
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

//    public function findOneBySomeField($value): ?Podcast
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
