<?php

namespace App\Repository;

use App\Entity\Neck;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Neck>
 *
 * @method Neck|null find($id, $lockMode = null, $lockVersion = null)
 * @method Neck|null findOneBy(array $criteria, array $orderBy = null)
 * @method Neck[]    findAll()
 * @method Neck[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NeckRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Neck::class);
    }

    //    /**
    //     * @return Neck[] Returns an array of Neck objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('n')
    //            ->andWhere('n.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('n.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Neck
    //    {
    //        return $this->createQueryBuilder('n')
    //            ->andWhere('n.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
