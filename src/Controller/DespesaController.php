<?php

namespace App\Controller;

use App\Exception\DuplicateEntityException;
use App\Facade\DespesaFacade;
use App\Form\DespesaType;
use App\Repository\DespesaRepository;
use App\Request\DespesaRequest;
use App\Trait\ControllerHelpers;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Exception\EntityNotFoundException;

#[Route('/despesa')]
class DespesaController extends AbstractController
{
    use ControllerHelpers;

    public function __construct(
        private DespesaFacade $despesaFacade,
        private DespesaRepository $despesaRepository
    ) 
    {  
    }

    
    #[Route(methods:'POST')]
    public function createAction(Request $request): Response
    {
        $despesaRequest = new DespesaRequest();
        $form = $this->createForm(DespesaType::class, $despesaRequest);
        $data = $this->getJson($request);

        $form->submit($data);

        if(!$form->isValid()){

            return new JsonResponse([
                'msg' => 'não foi possível realizar o cadastro',
                'erros' => $this->getErrorMessages($form)
            ]);
        }

        try{

            $despesa = $this->despesaFacade->createDespesa($despesaRequest);

            return new JsonResponse([

                'msg' => 'despesa cadastrada com sucesso',
                'despesa' => $despesa
            ]);

        }catch(DuplicateEntityException $e){

            return new JsonResponse([

                'msg' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route(methods: 'GET')]
    public function readAllAction(Request $request): Response
    {
        $search =  $request->query->get('descricao');
        $despesaList = is_null($search) ? $this->despesaRepository->findAll() : $this->despesaRepository->findByDescription($search);

        return new JsonResponse($despesaList);
    }

    #[Route('/{id}', methods: 'GET')]
    public function readByIdAction(int $id): Response
    {
        $despesa = $this->despesaRepository->find($id);
        $http_code = is_null($despesa) ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;

        return new JsonResponse($despesa, $http_code);
    }

    #[Route('/{year}/{month}')]
    public function readByDate(int $year, int $month)
    {
        $despesaList = $this->despesaRepository->findByDate($year, $month);
        $http_code = !empty($despesaList) ? Response::HTTP_OK : Response::HTTP_NO_CONTENT;

        return new JsonResponse($despesaList, $http_code);
    }

    #[Route('/{id}', methods: 'PUT')]
    public function updateAction(int $id, Request $request)
    {
        try {

            $despesa = $this->despesaFacade->getEntityFromId($id);
            $despesaRequest = DespesaRequest::createFrom($despesa);

            $form = $this->createForm(DespesaType::class, $despesaRequest);
            $data = $this->getJson($request);

            $form->submit($data);

            if (!$form->isValid()) {

                return new JsonResponse([

                    'msg' => 'Não foi possível atualizar o registro',
                    'erros' => $this->getErrorMessages($form)
                ]);
            }

            $despesaAtualizada = $this->despesaFacade->updateDespesa($despesa, $despesaRequest);

            return new JsonResponse([

                'msg' => 'despesa Atualizada com sucesso',
                'despesa' => $despesaAtualizada
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

            $despesa = $this->despesaFacade->getEntityFromId($id);
            $this->despesaRepository->remove($despesa, true);

            return new JsonResponse([

                'msg' => 'despesa deletada com sucesso'
            ]);
        } catch (EntityNotFoundException $e) {

            return new JsonResponse([
                'msg' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
