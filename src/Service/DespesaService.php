<?php

namespace App\Service;

use App\Entity\Despesa;
use App\Repository\DespesaRepository;

class DespesaService
{
    public function __construct(
        private DespesaRepository $despesaRepository
    ) {
    }
    public function isDuplicate(Despesa $despesa): bool
    {
        $despesaDuplicate = $this->despesaRepository->findDuplicate($despesa);
        return !is_null($despesaDuplicate);
    }
}
