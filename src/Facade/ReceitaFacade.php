<?php

namespace App\Facade;

use App\Repository\ReceitaRepository;
use Doctrine\ORM\EntityManagerInterface;

class ReceitaFacade extends AbstractContaContabilFacade
{

    public function __construct(

        ReceitaRepository $repository,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct($repository, $entityManager);
    }
}
