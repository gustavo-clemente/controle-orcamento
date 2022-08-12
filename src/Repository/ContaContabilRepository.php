<?php

namespace App\Repository;

use App\Entity\ContaContabil;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class ContaContabilRepository extends ServiceEntityRepository
{

    public function add(ContaContabil $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ContaContabil $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByDescription(string $descricao)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.descricao LIKE :descricao')
            ->setParameter('descricao', "%{$descricao}%")
            ->getQuery()
            ->getResult()
        ;
    }

    public function findDuplicate(ContaContabil $entity): ?ContaContabil
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.descricao = :descricao')
            ->andWhere('MONTH(c.data) = MONTH(:data)')
            ->andWhere('YEAR(c.data) = YEAR(:data)')
            ->setParameters([
                'descricao' => $entity->getDescricao(),
                'data' => $entity->getData()
            ])
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findByDate(int $year, int $month)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('YEAR(c.data) = :ano')
            ->andWhere('MONTH(c.data) = :mes')
            ->setParameters([

                'ano' => $year,
                'mes' => $month
            ])
            ->getQuery()
            ->getResult();
    }
}
