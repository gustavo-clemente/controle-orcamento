<?php

namespace App\Controller;

use App\Helper\DespesaFactory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\DespesaRepository;
use App\Service\DespesaService;
use App\Trait\ContaContabilRoutes;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/despesa')]
class DespesaController extends AbstractController
{
    use ContaContabilRoutes;

    /** @param EntityManager $entityManager */
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DespesaFactory $factory,
        private DespesaRepository $repository,
        private DespesaService $service
    ) {
    }

}
