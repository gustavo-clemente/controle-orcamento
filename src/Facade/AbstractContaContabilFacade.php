<?php

namespace App\Facade;

use App\Entity\AbstractContaContabil;
use App\Repository\AbstractContaContabilRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Exception\EntityNotFoundException;
use App\Exception\DuplicateEntityException;

abstract class AbstractContaContabilFacade
{
    public function __construct(

        protected AbstractContaContabilRepository $repository,
        protected EntityManagerInterface $entityManager
    ) {
    }

    public function create(AbstractContaContabil $contaContabil): AbstractContaContabil
    {
        if ($this->isDuplicate($contaContabil)) {

            throw new DuplicateEntityException('Já existe um registro cadastrado com o mesmo nome no mês informado');
        }

        $this->repository->add($contaContabil, true);

        return $contaContabil;
    }

    public function update(AbstractContaContabil $contaContabil): AbstractContaContabil
    {

        if ($this->isDuplicate($contaContabil)) {

            throw new DuplicateEntityException('Já existe um registro cadastrada com o mesmo nome no mês informado');
        }

        $this->entityManager->flush();

        return $contaContabil;
    }

    public function isDuplicate(AbstractContaContabil $contaContabil): bool
    {
        $contaContabilDuplicate = $this->repository->findMonthDescription($contaContabil);

        return !is_null($contaContabilDuplicate);
    }

    public function getEntityFromId(int $id): AbstractContaContabil
    {
        $contaContabil = $this->repository->find($id);

        if (is_null($contaContabil)) {

            throw new EntityNotFoundException('O id informado não corresponde a nenhum registro');
        }

        return $contaContabil;
    }
}
