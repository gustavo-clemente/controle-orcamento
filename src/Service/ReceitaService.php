<?php

namespace App\Service;

use App\Entity\Receita;
use App\Repository\ReceitaRepository;

class ReceitaService
{
    public function __construct(
        private ReceitaRepository $receitaRepository
    ) {
    }
    public function isDuplicate(Receita $receita): bool
    {
        $receitaDuplicate = $this->receitaRepository->findDuplicate($receita);
        return !is_null($receitaDuplicate);
    }
}
