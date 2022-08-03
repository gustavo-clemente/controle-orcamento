<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helper\ReceitaFactory;
use App\Repository\ReceitaRepository;
use App\Service\ReceitaService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[Route('/receita')]
class ReceitaController extends AbstractController
{
    /** @param EntityManager $entityManager */
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ReceitaFactory $receitaFactory,
        private ReceitaRepository $receitaRepository,
        private ReceitaService $receitaService
    ) {
    }

    #[Route(methods: 'POST')]
    public function create(Request $request): Response
    {
        $requestBody = $request->getContent();
        $receita = $this->receitaFactory->create($requestBody);

        if (gettype($receita) == 'array') {

            return new JsonResponse([

                'msg' => 'Não foi possível cadastrar a receita',
                'erros' => $receita
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($this->receitaService->isDuplicate($receita)) {

            return new JsonResponse([

                'msg' => 'Já existe uma receita com o mesmo nome no mês informado'
            ], Response::HTTP_BAD_REQUEST);
        }

        $this->receitaRepository->add($receita, true);

        return new JsonResponse([
            'msg' => 'receita cadastrada com sucesso',
            'receita' => $receita
        ], Response::HTTP_CREATED);
    }

    #[Route(methods: 'GET')]
    public function readAll(): Response
    {
        $receitaList = $this->receitaRepository->findAll();

        return new JsonResponse($receitaList);
    }

    #[Route('/{id}', methods: 'GET')]
    public function readById(int $id): Response
    {
        $receita = $this->receitaRepository->find($id);
        $http_code = is_null($receita) ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;

        return new JsonResponse($receita, $http_code);
    }

    #[Route('/{id}',methods:'PUT')]
    public function update(Request $request, int $id)
    {

        $requestBody = $request->getContent();
        $receitaAtualizada = $this->receitaFactory->create($requestBody);

        $receitaExistente = $this->receitaRepository->find($id);

        if (is_null($receitaExistente)) {

            return new JsonResponse([

                'msg' => 'O ID informado não corresponde a nenhuma receita'

            ], Response::HTTP_NOT_FOUND);
        }

        if ($this->receitaService->isDuplicate($receitaAtualizada)) {

            return new JsonResponse([

                'msg' => 'Já existe uma receita com o mesmo nome no mês informado'
            ], Response::HTTP_BAD_REQUEST);
        }

        $receitaExistente
            ->setDescricao($receitaAtualizada->getDescricao())
            ->setValor($receitaAtualizada->getValor())
            ->setData($receitaAtualizada->getData());

        $this->entityManager->flush();

        return new JsonResponse([
            'msg' => 'receita atualizada com sucesso',
            'receita' => $receitaExistente
        ], Response::HTTP_OK);
    }
}
