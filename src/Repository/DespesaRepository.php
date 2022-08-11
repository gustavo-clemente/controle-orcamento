<?php

namespace App\Repository;

use App\Repository\ContaContabilRepository;
use App\Entity\Despesa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Despesa>
 *
 * @method Despesa|null find($id, $lockMode = null, $lockVersion = null)
 * @method Despesa|null findOneBy(array $criteria, array $orderBy = null)
 * @method Despesa[]    findAll()
 * @method Despesa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DespesaRepository extends ContaContabilRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Despesa::class);
    }

}
