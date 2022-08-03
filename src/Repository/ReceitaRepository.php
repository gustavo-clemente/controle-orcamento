<?php

namespace App\Repository;

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
 */
class ReceitaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Receita::class);
    }

    public function add(Receita $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Receita $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findDuplicate(Receita $entity): ?Receita
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.descricao = :descricao')
            ->andWhere('MONTH(r.data) = MONTH(:data)')
            ->andWhere('YEAR(r.data) = YEAR(:data)')
            ->setParameters([
                'descricao' => $entity->getDescricao(),
                'data' => $entity->getData()
            ])
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
