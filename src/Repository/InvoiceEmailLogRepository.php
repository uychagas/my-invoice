<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\InvoiceEmailLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InvoiceEmailLog>
 */
class InvoiceEmailLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvoiceEmailLog::class);
    }
}
