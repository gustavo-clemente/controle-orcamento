<?php

namespace App\Facade;

use App\Entity\Receita;
use App\Repository\ReceitaRepository;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;

class ReceitaFacade extends AbstractContaContabilFacade
{

    public function __construct(

        ReceitaRepository $repository,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct($repository, $entityManager);
    }

    public function createReceita(string $descricao, float $valor, DateTimeInterface $data): Receita
    {
        $receita = new Receita();
        $receita
            ->setDescricao($descricao)
            ->setValor($valor)
            ->setData($data)
            
        ;

        return $receita;
    }
}
