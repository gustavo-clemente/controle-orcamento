<?php

namespace App\Facade;

use App\Request\ReceitaRequest;
use App\Entity\Receita;
use App\Exception\DuplicateEntityException;
use App\Exception\EntityNotFoundException;
use App\Repository\ReceitaRepository;
use Doctrine\ORM\EntityManagerInterface;

class ReceitaFacade
{

    public function __construct(

        private ReceitaRepository $receitaRepository,
        private EntityManagerInterface $entityManager
    )
    {
        
    }
    public function createReceita(ReceitaRequest $request): Receita
    {
        $receita = $this->getEntityFromRequest($request);

        if($this->isDuplicate($receita)){

            throw new DuplicateEntityException('Já existe uma receita cadastrada com o mesmo nome no mês informado');
        }

        $this->receitaRepository->add($receita,true);

        return $receita;
    }

    public function updateReceita(Receita $receita, ReceitaRequest $receitaRequest): Receita
    {
        $receita = $this->getEntityFromRequest($receitaRequest, $receita);

        if($this->isDuplicate($receita)){

            throw new DuplicateEntityException('Já existe uma receita cadastrada com o mesmo nome no mês informado');
        }

        $this->entityManager->flush();

        return $receita;
    }

    public function isDuplicate(Receita $receita): bool
    {
        $receitaDuplicate = $this->receitaRepository->findDuplicate($receita);

        return !is_null($receitaDuplicate);
    }

    public function getEntityFromRequest(ReceitaRequest $request,  ?Receita $receita = null): Receita
    {
        if(is_null($receita)){
            $receita = new Receita();
        }
        
        $receita
        ->setDescricao($request->descricao)
        ->setValor($request->valor)
        ->setData($request->data);

        return $receita;
    }

    public function getEntityFromId(int $id): Receita
    {
        $receita = $this->receitaRepository->find($id);

        if(is_null($receita)){

            throw new EntityNotFoundException('O id informado não corresponde a nenhuma receita');
        }

        return $receita;
    }
}