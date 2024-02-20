<?php

namespace App\Repository;

use App\Entity\Invoice;
use App\Entity\Login;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\AbstractQuery;

/**
 * @extends ServiceEntityRepository<Invoice>
 *
 * @method Invoice|null find($id, $lockMode = null, $lockVersion = null)
 * @method Invoice|null findOneBy(array $criteria, array $orderBy = null)
 * @method Invoice[]    findAll()
 * @method Invoice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvoiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invoice::class);
    }

    public function findAllQuery() : Query
    {
        return $this->createQueryBuilder('i')
            ->orderBy('i.date', 'DESC')
            ->getQuery()
            ;
    }

    public function findAllActive(): array
    {
        return $this->createQueryBuilder('i')
            ->orderBy('i.date', 'DESC')
            ->andWhere('i.discharge = :discharge')
            ->setParameter('discharge', false)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Invoice[] Returns an array of Invoice objects
     */
    public function findByText($value): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.number LIKE :val')
            ->setParameter('val', "%$value%")
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function findInvoicesForLoggedInUser(Login $login): array
    {
        $results = $this->createQueryBuilder('i')
        ->select(['i.id', 'i.number', 'i.price', 'i.date', 'c.name as customer_name'])
        ->innerJoin('i.customer', 'c')
        ->innerJoin('c.login', 'l')
        ->andWhere('l = :login')
        ->setParameter('login', $login)
        ->orderBy('i.date', 'DESC')
        ->getQuery()
        ->getResult(AbstractQuery::HYDRATE_ARRAY);

        foreach ($results as &$result) {
            $result['date'] = $result['date']->format('d/m/Y');
        }

        return $results;
    }    

    public function findByTextQuery(string $value): Query
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.number LIKE :val')
            ->setParameter('val', "%$value%")
            ->orderBy('i.number', 'DESC')
            ->getQuery()
            ;
    }

//    public function findOneBySomeField($value): ?Invoice
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

}