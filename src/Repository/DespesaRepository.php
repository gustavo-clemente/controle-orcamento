<?php

namespace App\Repository;

use App\Repository\AbstractContaContabilRepository;
use App\Entity\Despesa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Despesa>
 *
 * @method Despesa|null find($id, $lockMode = null, $lockVersion = null)
 * @method Despesa|null findOneBy(array $criteria, array $orderBy = null)
 * @method Despesa|null findMonthDuplicate(Despesa $entity)
 * @method Despesa[]    findAll()
 * @method Despesa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DespesaRepository extends AbstractContaContabilRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Despesa::class);
    }

    public function getTotalPerCategory($year, $month)
    {
        return $this->createQueryBuilder('d')
            ->select('SUM(d.valor) as total')
            ->addSelect('d.categoria')
            ->andWhere('YEAR(d.data) = :ano')
            ->andWhere('MONTH(d.data) = :mes')
            ->addGroupBy('d.categoria')
            ->orderBy('d.categoria')
            ->setParameters([
                'ano' => $year,
                'mes' => $month
            ])
            ->getQuery()
            ->getResult();
    }

}
