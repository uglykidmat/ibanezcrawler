<?php

namespace App\Repository;

use App\Entity\Guitar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Guitar>
 *
 * @method Guitar|null find($id, $lockMode = null, $lockVersion = null)
 * @method Guitar|null findOneBy(array $criteria, array $orderBy = null)
 * @method Guitar[]    findAll()
 * @method Guitar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GuitarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Guitar::class);
    }

    //    /**
    //     * @return Guitar[] Returns an array of Guitar objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('g.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    public function countFamilies(): int
    {
        $conn = $this->getEntityManager()->getConnection();

        // Correct native SQL query
        $sql = 'SELECT COUNT(DISTINCT family) AS family_count FROM guitar';

        $resultSet = $conn->executeQuery($sql);
        $result = $resultSet->fetchAssociative();
        // echo ($result['family_count'] . PHP_EOL);

        return $result['family_count'] ?? 0; // Return the count, or 0 if null
    }

}
