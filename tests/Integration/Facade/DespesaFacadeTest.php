<?php

namespace App\Test\Integration\Facade;

use App\Exception\DuplicateEntityException;
use App\Facade\DespesaFacade;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DespesaFacadeTest extends KernelTestCase
{
    private DespesaFacade $facade;

    public function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        $this->facade = $container->get(DespesaFacade::class);
    }

    public function testCreate()
    {
        $despesa = $this->facade->createDespesa('Nova Despesa', 700, new DateTime('2022-05-05'), 'Alimentação');
        $this->facade->create($despesa);

        $despesaConsulta = $this->facade->getEntityFromId($despesa->getId());

        self::assertSame($despesaConsulta, $despesa);
    }

    public function testCreateDuplicate()
    {
        $this->expectException(DuplicateEntityException::class);
        
        $despesa = $this->facade->createDespesa('Nova Despesa', 700, new DateTime('2022-05-05'), 'Alimentação');
        $despesaCadastrada = $this->facade->create($despesa);

        $this->facade->create($despesaCadastrada);
    }

    public function testUpdate()
    {
        $despesa = $this->facade->createDespesa('Nova Despesa', 700, new DateTime('2022-05-05'), 'Alimentação');
        $this->facade->create($despesa);

        $id = $despesa->getId();

        $despesa
            ->setDescricao('Descricao Atualizada')
            ->setValor(600)
        ;

        $this->facade->update($despesa);
        $despesaAtualizada = $this->facade->getEntityFromId($id);

        self::assertEquals('Descricao Atualizada',$despesaAtualizada->getDescricao());
        self::assertEquals(600, $despesaAtualizada->getValor());
    }

    public function testUpdateDuplicate()
    {
        $this->expectException(DuplicateEntityException::class);

        $despesa = $this->facade->createDespesa('Nova Despesa', 700, new DateTime('2022-05-05'), 'Alimentação');
        $this->facade->create($despesa);

        $despesaAtualizada = $this->facade->getEntityFromId($despesa->getId());

        $this->facade->update($despesaAtualizada);
    }
}
