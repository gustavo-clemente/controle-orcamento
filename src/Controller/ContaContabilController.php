<?php

namespace App\Controller;

use App\Entity\ContaContabil;
use App\Repository\ContaContabilRepository;
use LogicException;
use ReflectionClass;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Trait\CreateViolationsArray;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class ContaContabilController extends AbstractController
{
    use CreateViolationsArray;

    protected string $contaContabilForm;
    private ReflectionClass $reflectionClass;


    public function __construct(
        protected string $contaContabilClass,
        protected ContaContabilRepository $repository,
        protected ValidatorInterface $validator,
        protected EntityManagerInterface $entityManager
    )
    {
        if(!$this->isClassValid()){

            throw new LogicException("O parâmetro 'contaContabilClass' deve ser uma classe herdada de ContaContabil");
        }

        $this->setFormType();
    }

    #[Route(methods: 'POST')]
    public function create(Request $request): Response
    {
        $requestBody = $request->getContent();
        $data = json_decode($requestBody, true);

        $form = $this->createForm($this->contaContabilForm);
        $form->submit($data);

        $contaContabil = $form->getData();

        $violations = $this->validator->validate($contaContabil);

        if (!$form->isValid()) {

            return new JsonResponse([

                'msg' => 'Não foi possível finalizar o cadastro',
                'erros' => $this->createViolationsArray($violations)
            ], Response::HTTP_BAD_REQUEST);
        }

        if (!is_null($this->repository->findDuplicate($contaContabil))) {

            return new JsonResponse([

                'msg' => 'Já existe um registro com o mesmo nome no mês informado'
            ], Response::HTTP_BAD_REQUEST);
        }

        $this->repository->add($contaContabil, true);

        return new JsonResponse([
            'msg' => 'cadastro realizado com sucesso',
            'registro' => $contaContabil
        ], Response::HTTP_CREATED);

    }

    #[Route(methods: 'GET')]
    public function readAll(Request $request): Response
    {
        $search = $request->query->get('descricao');
        $contaContabilList = is_null($search) ? $this->repository->findAll() : $this->repository->findByDescription($search);

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
        $data = json_decode($requestBody, true);

        $contaContabilExistente = $this->repository->find($id);

        $form = $this->createForm($this->contaContabilForm, $contaContabilExistente);
        $form->submit($data);

        $contaContabilAtualizada = $form->getData();
        

        $violations = $this->validator->validate($contaContabilAtualizada);

        if (!$form->isValid()) {

            return new JsonResponse([

                'msg' => 'Não foi possível finalizar o cadastro',
                'erros' => $this->createViolationsArray($violations)
            ], Response::HTTP_BAD_REQUEST);
        }

        if (!is_null($this->repository->findDuplicate($contaContabilAtualizada))) {

            return new JsonResponse([

                'msg' => 'Já existe um registro com o mesmo nome no mês informado'
            ], Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->flush();

        return new JsonResponse([
            'msg' => 'registro atualizado com sucesso',
            'registro' => $contaContabilAtualizada
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

    private function isClassValid()
    {
        $this->reflectionClass = new ReflectionClass($this->contaContabilClass);

        return $this->reflectionClass->isInstantiable() && $this->reflectionClass->isSubclassOf(ContaContabil::class);
    }

    private function setFormType(){

        $formName = $this->reflectionClass->getShortName() . 'Type';
        $this->contaContabilForm = 'App\\Form\\'.$formName;
    }
}
