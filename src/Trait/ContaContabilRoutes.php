<?php

namespace App\Trait;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

trait ContaContabilRoutes
{
    #[Route(methods: 'POST')]
    public function create(Request $request): Response
    {
        $requestBody = $request->getContent();
        $contaContabil = $this->factory->create($requestBody);

        if (gettype($contaContabil) == 'array') {

            return new JsonResponse([

                'msg' => 'Não foi possível finalizar o cadastro',
                'erros' => $contaContabil
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($this->service->isDuplicate($contaContabil)) {

            return new JsonResponse([

                'msg' => 'Já existe um registro com o mesmo nome no mês informado'
            ], Response::HTTP_BAD_REQUEST);
        }

        $this->repository->add($contaContabil, true);

        return new JsonResponse([
            'msg' => 'cadastro realizado com sucesso',
            'receita' => $contaContabil
        ], Response::HTTP_CREATED);
    }

    #[Route(methods: 'GET')]
    public function readAll(): Response
    {
        $contaContabilList = $this->repository->findAll();

        return new JsonResponse($contaContabilList);
    }

    #[Route('/{id}', methods: 'GET')]
    public function readById(int $id): Response
    {
        $contaContabil = $this->repository->find($id);
        $http_code = is_null($contaContabil) ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;

        return new JsonResponse($contaContabil, $http_code);
    }

    #[Route('/{id}', methods: 'PUT')]
    public function update(Request $request, int $id)
    {

        $requestBody = $request->getContent();
        $contaContabilAtualizada = $this->factory->create($requestBody);

        $contaContabilExistente = $this->repository->find($id);

        if (gettype($contaContabilAtualizada) == 'array') {

            return new JsonResponse([

                'msg' => 'Não foi possível realizar o cadastro',
                'erros' => $contaContabilAtualizada
            ], Response::HTTP_BAD_REQUEST);
        }

        if (is_null($contaContabilExistente)) {

            return new JsonResponse([

                'msg' => 'O ID informado não corresponde a nenhum registro'

            ], Response::HTTP_NOT_FOUND);
        }

        if ($this->service->isDuplicate($contaContabilAtualizada)) {

            return new JsonResponse([

                'msg' => 'Já existe um registro com o mesmo nome no mês informado'
            ], Response::HTTP_BAD_REQUEST);
        }

        $contaContabilExistente
            ->setDescricao($contaContabilAtualizada->getDescricao())
            ->setValor($contaContabilAtualizada->getValor())
            ->setData($contaContabilAtualizada->getData());

        $this->entityManager->flush();

        return new JsonResponse([
            'msg' => 'registro atualizado com sucesso',
            'receita' => $contaContabilExistente
        ], Response::HTTP_OK);
    }

    #[Route('/{id}', methods: 'DELETE')]
    public function delete(int $id)
    {
        $contaContabil = $this->repository->find($id);

        if (is_null($contaContabil)) {

            return new JsonResponse([

                'msg' => 'O ID informado não corresponde a nenhum registro'

            ], Response::HTTP_NOT_FOUND);
        }
        $this->repository->remove($contaContabil, true);

        return new JsonResponse([
            'msg' => 'registro deletado com sucesso'
        ]);
    }
}