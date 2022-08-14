<?php

namespace App\Controller;

use App\Facade\ReceitaFacade;
use App\Form\ReceitaType;
use App\Repository\ReceitaRepository;
use App\Request\ReceitaRequest;
use App\Trait\ControllerHelpers;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Exception\DuplicateEntityException;
use App\Exception\EntityNotFoundException;

#[Route('/receita')]
class ReceitaController extends AbstractController
{
    use ControllerHelpers;

    public function __construct(
        private ReceitaFacade $receitaFacade,
        private ReceitaRepository $receitaRepository
    ) {
    }

    #[Route(methods: 'POST')]
    public function createAction(Request $request): Response
    {
        $receitaRequest = new ReceitaRequest();
        $form = $this->createForm(ReceitaType::class, $receitaRequest);
        $data = $this->getJson($request);

        $form->submit($data);

        if (!$form->isValid()) {

            return new JsonResponse([

                'msg' => 'não foi possivel finalizar o cadastro',
                'erros' => $this->getErrorMessages($form)
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            $receita = $this->receitaFacade->createReceita($receitaRequest);

            return new JsonResponse([

                'msg' => 'Receita Cadastrada com sucesso',
                'receita' => $receita
            ]);
        } catch (DuplicateEntityException $e) {

            return new JsonResponse([

                'msg' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route(methods: 'GET')]
    public function readAllAction(Request $request): Response
    {
        $search =  $request->query->get('descricao');
        $receitaList = is_null($search) ? $this->receitaRepository->findAll() : $this->receitaRepository->findByDescription($search);

        return new JsonResponse($receitaList);
    }

    #[Route('/{id}', methods: 'GET')]
    public function readByIdAction(int $id): Response
    {
        $receita = $this->receitaRepository->find($id);
        $http_code = is_null($receita) ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;

        return new JsonResponse($receita, $http_code);
    }

    #[Route('/{year}/{month}')]
    public function readByDate(int $year, int $month)
    {
        $receitaList = $this->receitaRepository->findByDate($year, $month);
        $http_code = !empty($receitaList) ? Response::HTTP_OK : Response::HTTP_NO_CONTENT;

        return new JsonResponse($receitaList, $http_code);
    }


    #[Route('/{id}', methods: 'PUT')]
    public function updateAction(int $id, Request $request)
    {
        try {

            $receita = $this->receitaFacade->getEntityFromId($id);
            $receitaRequest = ReceitaRequest::createFrom($receita);

            $form = $this->createForm(ReceitaType::class, $receitaRequest);
            $data = $this->getJson($request);

            $form->submit($data);

            if (!$form->isValid()) {

                return new JsonResponse([

                    'msg' => 'Não foi possível atualizar o registro',
                    'erros' => $this->getErrorMessages($form)
                ]);
            }

            $receitaAtualizada = $this->receitaFacade->updateReceita($receita, $receitaRequest);

            return new JsonResponse([

                'msg' => 'receita Atualizada com sucesso',
                'receita' => $receitaAtualizada
            ]);
        } catch (EntityNotFoundException $e) {

            return new JsonResponse([
                'msg' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        } catch (DuplicateEntityException $e) {

            return new JsonResponse([
                'msg' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', methods: 'DELETE')]
    public function deleteAction(int $id)
    {
        try {

            $receita = $this->receitaFacade->getEntityFromId($id);
            $this->receitaRepository->remove($receita, true);

            return new JsonResponse([

                'msg' => 'receita deletada com sucesso'
            ]);
        } catch (EntityNotFoundException $e) {

            return new JsonResponse([
                'msg' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
