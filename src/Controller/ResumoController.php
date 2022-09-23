<?php

namespace App\Controller;

use App\Facade\RelatorioFacade;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/resumo')]
class ResumoController extends AbstractController
{

    public function __construct(

        private RelatorioFacade $relatorioFacade
    ) {
    }
    #[Route('/{year}/{month}')]
    public function readSumaryAction(int $year, int $month)
    {
        $sumary = $this->relatorioFacade->getSumary($year, $month);

        return new JsonResponse(

            ["resultado" => $sumary]
        );
    }
    
}
