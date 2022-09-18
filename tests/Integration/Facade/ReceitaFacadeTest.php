<?php

namespace App\Test\Integration\Facade;

use App\Facade\ReceitaFacade;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Exception\DuplicateEntityException;

class ReceitaFacadeTest extends KernelTestCase
{
    private ReceitaFacade $facade;

    public function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        $this->facade = $container->get(ReceitaFacade::class);
    }

    public function testCreate()
    {
        $receita = $this->facade->createReceita('Nova Receita', 700, new DateTime('2022-05-05'));
        $this->facade->create($receita);

        $receitaConsulta = $this->facade->getEntityFromId($receita->getId());

        self::assertSame($receitaConsulta, $receita);
    }

    public function testCreateDuplicate()
    {
        $this->expectException(DuplicateEntityException::class);
        
        $receita = $this->facade->createReceita('Nova Receita', 700, new DateTime('2022-05-05'));
        $receitaCadastrada = $this->facade->create($receita);

        $this->facade->create($receitaCadastrada);
    }

    public function testUpdate()
    {
        $receita = $this->facade->createReceita('Nova Receita', 700, new DateTime('2022-05-05'));
        $this->facade->create($receita);

        $id = $receita->getId();

        $receita
            ->setDescricao('Descricao Atualizada')
            ->setValor(600)
        ;

        $this->facade->update($receita);
        $receitaAtualizada = $this->facade->getEntityFromId($id);

        self::assertEquals('Descricao Atualizada',$receitaAtualizada->getDescricao());
        self::assertEquals(600, $receitaAtualizada->getValor());
    }

    public function testUpdateDuplicate()
    {
        $this->expectException(DuplicateEntityException::class);

        $receita = $this->facade->createReceita('Nova Receita', 700, new DateTime('2022-05-05'));
        $this->facade->create($receita);

        $receitaAtualizada = $this->facade->getEntityFromId($receita->getId());

        $this->facade->update($receitaAtualizada);
    }
}