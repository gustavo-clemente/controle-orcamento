<?php

namespace App\Controller;

use App\Repository\DespesaRepository;
use App\Repository\ReceitaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/resumo')]
class ResumoController extends AbstractController
{

    public function __construct(

        private ReceitaRepository $receitaRepository,
        private DespesaRepository $despesaRepository
    ) {
    }
    #[Route('/{year}/{month}')]
    public function getSumary(int $year, int $month)
    {
        $totalReceita = $this->receitaRepository->getTotal($year, $month);
        $totalDespesa = $this->despesaRepository->getTotal($year,$month);
        $totalCategoria = $this->despesaRepository->getTotalPerCategory($year,$month);

        $saldoFinal = $totalReceita - $totalDespesa;

        return new JsonResponse([

            'totalReceita' => $totalReceita,
            'totalDespesa' => $totalDespesa,
            'saldoFinal' => $saldoFinal,
            'TotalCategoria' => $totalCategoria
        ]);
    }
}
