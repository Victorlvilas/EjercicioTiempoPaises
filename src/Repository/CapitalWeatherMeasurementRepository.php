<?php

namespace App\Repository;

use App\Entity\CapitalWeatherMeasurement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CapitalWeatherMeasurement>
 */
class CapitalWeatherMeasurementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CapitalWeatherMeasurement::class);
    }

    public function findTopCapitalsByMaxTemperature(int $limit): array
    {
        return $this->createQueryBuilder('m')
            ->select('c.capital', 'MAX(m.temperature) as maxTemperature', 'MAX(m.weatherLat) as weatherLat', 'MAX(m.weatherLng) as weatherLng')
            ->join('m.country', 'c')
            ->groupBy('c.capital')
            ->orderBy('maxTemperature', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return CapitalWeatherMeasurement[] Returns an array of CapitalWeatherMeasurement objects
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

    //    public function findOneBySomeField($value): ?CapitalWeatherMeasurement
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
