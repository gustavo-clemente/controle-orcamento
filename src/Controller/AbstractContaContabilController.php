<?php

namespace App\Controller;

use App\Entity\AbstractContaContabil;
use App\Facade\AbstractContaContabilFacade;
use App\Repository\AbstractContaContabilRepository;
use App\Trait\ControllerHelpers;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Exception\DuplicateEntityException;
use Symfony\Component\Routing\Annotation\Route;
use App\Exception\EntityNotFoundException;

abstract class AbstractContaContabilController extends AbstractController
{
    use ControllerHelpers;

    public function __construct(
        protected AbstractContaContabilRepository $repository,
        protected AbstractContaContabilFacade $facade     
    )
    {
    }

    #[Route(methods:'POST')]
    public function createAction(Request $request): Response
    {
        $contaContabil  = $this->getContaContabilEntity();
        $form = $this->createForm($this->getContaContabilType(), $contaContabil);
        $data = $this->getJson($request);

        $form->submit($data);

        if(!$form->isValid()){

            return new JsonResponse([
                'msg' => 'não foi possível realizar o cadastro',
                'erros' => $this->getErrorMessages($form)
            ]);
        }

        try{

            $contaContabil = $this->facade->create($contaContabil);

            return new JsonResponse([

                'msg' => 'despesa cadastrada com sucesso',
                'registro' => $contaContabil
            ]);

        }catch(DuplicateEntityException $e){

            return new JsonResponse([

                'msg' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route(methods:'GET')]
    public function readAllAction(Request $request): Response
    {
        $search =  $request->query->get('descricao');
        $contaCotabilList = is_null($search) ? $this->repository->findAll() : $this->repository->findByDescription($search);

        return new JsonResponse($contaCotabilList);
    }

    #[Route('/{id}', methods: 'GET')]
    public function readByIdAction(int $id): Response
    {
        $contaContabil = $this->repository->find($id);
        $http_code = is_null($contaContabil) ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;

        return new JsonResponse($contaContabil, $http_code);
    }

    #[Route('/{year}/{month}')]
    public function readByDate(int $year, int $month)
    {
        $contaCotabilList = $this->repository->findByDate($year, $month);
        $http_code = !empty($contaCotabilList) ? Response::HTTP_OK : Response::HTTP_NO_CONTENT;

        return new JsonResponse($contaCotabilList, $http_code);
    }

    #[Route('/{id}', methods: 'PUT')]
    public function updateAction(int $id, Request $request)
    {
        try {

            $contaContabil = $this->facade->getEntityFromId($id);

            $form = $this->createForm($this->getContaContabilType(), $contaContabil);
            $data = $this->getJson($request);

            $form->submit($data);

            if (!$form->isValid()) {

                return new JsonResponse([

                    'msg' => 'Não foi possível atualizar o registro',
                    'erros' => $this->getErrorMessages($form)
                ]);
            }

            $contaContabilAtualizada = $this->facade->update($contaContabil);

            return new JsonResponse([

                'msg' => 'registro atualizado com sucesso',
                'receita' => $contaContabilAtualizada
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

            $contaContabil = $this->facade->getEntityFromId($id);
            $this->repository->remove($contaContabil, true);

            return new JsonResponse([

                'msg' => 'registro deletado com sucesso'
            ]);
        } catch (EntityNotFoundException $e) {

            return new JsonResponse([
                'msg' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }

    abstract protected function getContaContabilType(): string;

    abstract protected function getContaContabilEntity(): AbstractContaContabil;
   

    
}