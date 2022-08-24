<?php

namespace App\Controller;

use App\Entity\Despesa;
use App\Facade\DespesaFacade;
use App\Form\DespesaType;
use App\Repository\DespesaRepository;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/despesa')]
class DespesaController extends AbstractContaContabilController
{
    public function __construct(
        DespesaRepository $despesaRepository,
        DespesaFacade $despesaFacade
        
    ) 
    {
        parent::__construct($despesaRepository,$despesaFacade);
    }


    protected function getContaContabilEntity(): Despesa
    {
        $despesa = new Despesa();

        return $despesa;
    }

    protected function getContaContabilType(): string
    {
        return DespesaType::class;
    }

}
