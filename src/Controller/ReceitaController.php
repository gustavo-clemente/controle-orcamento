<?php

namespace App\Controller;

use App\Facade\ReceitaFacade;
use App\Form\ReceitaType;
use App\Repository\ReceitaRepository;
use App\Trait\ControllerHelpers;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Receita;

#[Route('/receita')]
class ReceitaController extends AbstractContaContabilController
{
    use ControllerHelpers;

    public function __construct(
        ReceitaRepository $receitaRepository,
        ReceitaFacade $receitaFacade
        
    ) 
    {
        parent::__construct($receitaRepository,$receitaFacade);
    }

    protected function getContaContabilEntity(): Receita
    {
        $receita = new Receita();

        return $receita;
    }

    protected function getContaContabilType(): string
    {
        return ReceitaType::class;
    }

}
