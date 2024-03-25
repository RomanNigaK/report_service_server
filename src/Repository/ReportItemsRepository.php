<?php

namespace App\Repository;

use App\Entity\Company;
use App\Entity\ReportItems;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReportItems>
 *
 * @method ReportItems|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReportItems|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReportItems[]    findAll()
 * @method ReportItems[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportItemsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReportItems::class);
    }

//    /**
//     * @return ReportItems[] Returns an array of ReportItems objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

public function findAllByMonth(string $date, Company $company): array
{
    // $qb = $this->createQueryBuilder('p')
    //     ->where('p.createAt LIKE :date')
    //     ->setParameter("date", $date.'%');
    // $query = $qb->getQuery();

    // return $query->execute();
    return $this->createQueryBuilder('reportItems')
        ->join('reportItems.report', 'report')
        ->join('report.user', 'user')
        ->join('user.company', 'company')
        ->where('company = :com')
        ->andWhere('report.createAt LIKE :date')
        ->setParameter("date", $date . '%')
        ->setParameter("com", $company)
        ->getQuery()
        ->getResult();
}
}
