<?php

namespace App\Facade;

use App\Repository\DespesaRepository;
use App\Repository\ReceitaRepository;

class RelatorioFacade
{
    public function __construct(
        private ReceitaRepository $receitaRepository,
        private DespesaRepository $despesaRepository
    ) {
    }

    public function getSumary(int $year, int $month): array
    {
        $totalReceita = $this->receitaRepository->getTotalMonth($year, $month);
        $totalDespesa = $this->despesaRepository->getTotalMonth($year, $month);
        $totalCategoria = $this->despesaRepository->getTotalPerCategory($year, $month);

        $saldoFinal = $totalReceita - $totalDespesa;

        return [

            'totalReceita' => $totalReceita,
            'totalDespesa' => $totalDespesa,
            'saldoFinal' => $saldoFinal,
            'TotalCategoria' => $totalCategoria
        ];
    }
}
