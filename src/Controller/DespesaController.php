<?php

namespace App\Controller;

use App\Controller\ContaContabilController;
use App\Entity\Despesa;
use App\Repository\DespesaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/despesa')]
class DespesaController extends ContaContabilController
{
    public function __construct(
        DespesaRepository $repository,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager
    ) 
    {

        parent::__construct(Despesa::class, $repository,$validator,$entityManager);
        
    }
}
