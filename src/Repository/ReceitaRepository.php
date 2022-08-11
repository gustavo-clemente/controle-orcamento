<?php

namespace App\Repository;

use App\Repository\ContaContabilRepository;
use App\Entity\Receita;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Receita>
 *
 * @method Receita|null find($id, $lockMode = null, $lockVersion = null)
 * @method Receita|null findOneBy(array $criteria, array $orderBy = null)
 * @method Receita[]    findAll()
 * @method Receita[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Receita|null findDuplicate(Receita $entity)
 */
class ReceitaRepository extends ContaContabilRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Receita::class);
    }
    
}
