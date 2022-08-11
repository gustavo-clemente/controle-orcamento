<?php

namespace App\Controller;

use App\Entity\Receita;
use App\Repository\ContaContabilRepository;
use App\Repository\ReceitaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/receita')]
class ReceitaController extends ContaContabilController
{

    public function __construct(
        ReceitaRepository $repository,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager
    ) 
    {

        parent::__construct(Receita::class,$repository,$validator,$entityManager);
        
    }
}
