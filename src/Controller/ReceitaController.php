<?php

namespace App\Controller;

use App\Entity\Receita;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helper\ReceitaFactory;
use App\Repository\ReceitaRepository;
use App\Service\ReceitaService;
use App\Trait\ContaContabilRoutes;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[Route('/receita')]
class ReceitaController extends AbstractController
{

    use ContaContabilRoutes;

    /** @param EntityManager $entityManager */
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ReceitaFactory $factory,
        private ReceitaRepository $repository,
        private ReceitaService $service
    ) {
    }

}
