<?php

namespace App\Repository;

use App\DataFixtures\VehicleFixtures;
use App\Entity\Model;
use App\Entity\Vehicle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vehicle>
 *
 * @method Vehicle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vehicle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vehicle[]    findAll()
 * @method Vehicle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehicleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicle::class);
    }
    public function findAllQuery() : Query
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.discharge IS NULL OR p.discharge = :discharge')
            ->setParameter('discharge', false)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ;
    }

    /**
     * @return VehicleFixtures[] Returns an array of VehicleFixtures objects
     */
    public function findByText(string $value): array
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.color LIKE :val')
            ->orWhere('v.plate LIKE :val')
            ->orWhere('v.fuel LIKE :val')
            ->orWhere('v.gearShit LIKE :val')
            ->orWhere('v.buyPrice LIKE :val')
            ->setParameter('val', "%$value%")
            ->setParameter('discharge', 0)
            ->orderBy('v.id', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByTextQuery(string $value): Query
    {
        return $this->createQueryBuilder('v')
            ->join('v.model', 'm')
            ->join('m.brand', 'b')
            ->where('m.name LIKE :val')
            ->orWhere('m.name LIKE :val')
            ->orWhere('b.name LIKE :val')
            ->orWhere('v.color LIKE :val')
            ->andWhere('v.discharge IS NULL OR v.discharge = :discharge')
            ->orWhere('v.plate LIKE :val')
            ->orWhere('v.fuel LIKE :val')
            ->orWhere('v.gearShit LIKE :val')
            ->orWhere('v.buyPrice LIKE :val')
            ->setParameter('val', "%$value%")
            ->setParameter('discharge', false)
            ->orderBy('v.id', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ;

    }

//    public function findOneBySomeField($value): ?VehicleFixtures
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}