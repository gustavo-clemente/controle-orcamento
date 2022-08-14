<?php

namespace App\Facade;

use App\Entity\Despesa;
use App\Repository\DespesaRepository;
use App\Request\DespesaRequest;
use Doctrine\ORM\EntityManagerInterface;
use App\Exception\DuplicateEntityException;
use App\Exception\EntityNotFoundException;

class DespesaFacade
{
    public function __construct(
        private DespesaRepository $despesaRepository,
        private EntityManagerInterface $entityManager
    )
    {
        
    }

    public function createDespesa(DespesaRequest $request): Despesa
    {
        $despesa = $this->getEntityFromRequest($request);

        if($this->isDuplicate($despesa)){

            throw new DuplicateEntityException('Já existe uma despesa cadastrada com o mesmo nome no mês informado');
        }

        $this->despesaRepository->add($despesa,true);

        return $despesa;
    }

    public function updateDespesa(Despesa $despesa, DespesaRequest $despesaRequest): Despesa
    {
        $despesa = $this->getEntityFromRequest($despesaRequest, $despesa);

        if($this->isDuplicate($despesa)){

            throw new DuplicateEntityException('Já existe uma despesa cadastrada com o mesmo nome no mês informado');
        }

        $this->entityManager->flush();

        return $despesa;
    }

    public function isDuplicate(Despesa $despesa): bool
    {
        $despesaDuplicate = $this->despesaRepository->findDuplicate($despesa);

        return !is_null($despesaDuplicate);
    }

    public function getEntityFromRequest(DespesaRequest $request,  ?Despesa $despesa = null): Despesa
    {
        if(is_null($despesa)){
            $despesa = new Despesa();
        }
        
        $despesa
        ->setDescricao($request->descricao)
        ->setValor($request->valor)
        ->setData($request->data);

        return $despesa;
    }

    public function getEntityFromId(int $id): Despesa
    {
        $despesa = $this->despesaRepository->find($id);

        if(is_null($despesa)){

            throw new EntityNotFoundException('O id informado não corresponde a nenhuma despesa');
        }

        return $despesa;
    }
}