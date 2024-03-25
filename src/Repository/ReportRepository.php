<?php

namespace App\Repository;

use App\Entity\Report;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class ReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Report::class);
    }

    //    /**
    //     * @return Report[] Returns an array of Report objects
    //     */
    public function findAllByCompanyDESC(string $id): array
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.company = ?1')
            ->orderBy('p.ordering', 'DESC')
            ->setParameter(1, $id);

        $query = $qb->getQuery();

        return $query->execute();
    }

    public function findAllByIdDESC(string $id): array
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.user = ?1')
            ->orderBy('p.ordering', 'DESC')
            ->setParameter(1, $id);
        $query = $qb->getQuery();

        return $query->execute();
    }
    // public function findAllByMonth(string $date): array
    // {
    //     // $qb = $this->createQueryBuilder('p')
    //     //     ->where('p.createAt LIKE :date')
    //     //     ->setParameter("date", $date.'%');
    //     // $query = $qb->getQuery();

    //     // return $query->execute();
    //     return $this->createQueryBuilder('reportItems')
    //         ->leftJoin('reportItems.report', 'report')
    //         ->where('p.createAt LIKE :date')
    //         ->setParameter("date", $date . '%')
    //         ->getQuery()
    //         ->getResult();
    // }
}
