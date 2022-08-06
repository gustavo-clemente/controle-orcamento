<?php

namespace App\Abstract\Repository;

use App\Abstract\Entity\ContaContabil;
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
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
