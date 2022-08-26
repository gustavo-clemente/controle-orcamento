<?php

namespace App\Facade;

use App\Entity\Despesa;
use App\Repository\DespesaRepository;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;

class DespesaFacade extends AbstractContaContabilFacade
{
    public function __construct(
        DespesaRepository $repository,
        EntityManagerInterface $entityManager
    )
    {
        parent::__construct($repository, $entityManager);
    }
    

    public function createDespesa(string $descricao, float $valor, DateTimeInterface $data, string $categoria): Despesa
    {
        $despesa = new Despesa();
        $despesa
            ->setCategoria($categoria)
            ->setDescricao($descricao)
            ->setValor($valor)
            ->setData($data)
            
        ;

        return $despesa;
    }
}