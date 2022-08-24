<?php

namespace App\Facade;

use App\Repository\DespesaRepository;
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
}