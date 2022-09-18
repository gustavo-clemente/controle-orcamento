<?php

namespace App\Test\Application\Controller;

use App\Entity\AbstractContaContabil;
use App\Entity\Despesa;
use App\Facade\AbstractContaContabilFacade;
use App\Facade\DespesaFacade;
use App\Test\Application\AbstractContaContabilControllerTest;
use DateTime;

class DespesaControllerTest extends AbstractContaContabilControllerTest
{
    protected string $endpoint = 'despesa';

    protected function createActionProvider(): array
    {
        $data1 = [

            'descricao' => 'despesaTeste',
            'valor' => 1200,
            'data' => '2022-09-01',
            'categoria' => 'Alimentação'
        ];

        $data2 = [

            'descricao' => 'despesaTeste',
            'valor' => 1200,
            'data' => '2022-01-01'
        ];

        $data3 = [

            'descricao' => 'despesa1',
            'valor' => 1200,
            'data' => '2022-01-01'
        ];

        $data4 = [

            'descricao' => '',
            'valor' => 1200,
            'data' => '2022-01-01'
        ];

        $data5 = [

            'descricao' => 'teste',
            'valor' => "abcde",
            'data' => '2022-01-01'
        ];

        $data6 = [

            'descricao' => 'teste',
            'valor' => -1200,
            'data' => '2022-01-01'
        ];

        $data7 = [

            'descricao' => 'teste',
            'valor' => 1200,
            'data' => 'sasasasass'
        ];

        $data8 = [

            'descricao' => 'despesaTeste',
            'valor' => 1200,
            'data' => '2022-09-01',
            'categoria' => 'teste inventado'
        ];

        $data9 = [

            'descricao' => 'teste'
        ];

        $schema1 = json_decode(file_get_contents($this->schemaPath . '/Despesa/created.json'));
        $schema2 = json_decode(file_get_contents($this->schemaPath . '/Common/status_message.json'));
        $schema3 = json_decode(file_get_contents($this->schemaPath . '/Despesa/invalid_request.json'));


        return [

            'teste cadastro realizado' => [$data1, 201, $schema1],
            'teste cadastro realizado sem informar categoria' => [$data2, 201, $schema1],
            'teste cadastro duplicado no mês' => [$data3, 400, $schema2],
            'teste cadastro descrição inválida' => [$data4, 400, $schema3],
            'teste cadastro valor inválido' => [$data5, 400, $schema3],
            'teste cadastro valor negativo' => [$data6, 400, $schema3],
            'teste cadastro data inválida' => [$data7, 400, $schema3],
            'teste cadastro categoria inválida' => [$data8, 400, $schema3],
            'teste cadastro com campos faltando' => [$data9, 400, $schema3]

        ];
    }

    protected function readAllActionProvider(): array
    {
        $schema = json_decode(file_get_contents($this->schemaPath . '/Despesa/list.json'));

        return [

            'busca geral' => ['', 50, $schema],
            'busca aproximada' => ['Despesa', 15,$schema],
            'busca específica' => ['Conta1', 4, $schema],
            'busca inválida' => ['sem correspondencia', 0, $schema]
        ];
    }

    protected function readByDateActionProvider(): array
    {
        $schema = json_decode(file_get_contents($this->schemaPath . '/Despesa/list.json'));

        return [

            'Janeiro de 2022' => [2022, 1, 5, $schema],
            'Março de 2022' => [2022, 3, 10, $schema],
            'Março de 2020' => [2020, 3, 5, $schema],
            'Sem resultado' => [2010, 1, 0, $schema]
        ];
    }

    protected function updateActionProvider(): array
    {
        $data1 = [

            'descricao' => 'despesaTeste',
            'valor' => 1200,
            'data' => '2022-09-01',
            'categoria' => 'Alimentação'
        ];

        $data2 = [

            'descricao' => 'despesaTeste',
            'valor' => 1200,
            'data' => '2022-01-01'
        ];

        $data3 = [

            'descricao' => 'despesa1',
            'valor' => 1200,
            'data' => '2022-01-01'
        ];

        $data4 = [

            'descricao' => '',
            'valor' => 1200,
            'data' => '2022-01-01'
        ];

        $data5 = [

            'descricao' => 'teste',
            'valor' => "abcde",
            'data' => '2022-01-01'
        ];

        $data6 = [

            'descricao' => 'teste',
            'valor' => -1200,
            'data' => '2022-01-01'
        ];

        $data7 = [

            'descricao' => 'teste',
            'valor' => 1200,
            'data' => 'sasasasass'
        ];

        $data8 = [

            'descricao' => 'despesaTeste',
            'valor' => 1200,
            'data' => '2022-09-01',
            'categoria' => 'teste inventado'
        ];

        $data9 = [

            'descricao' => 'teste'
        ];

        $schema1 = json_decode(file_get_contents($this->schemaPath . '/Despesa/created.json'));
        $schema2 = json_decode(file_get_contents($this->schemaPath . '/Common/status_message.json'));
        $schema3 = json_decode(file_get_contents($this->schemaPath . '/Despesa/invalid_request.json'));


        return [

            'teste cadastro realizado' => [$data1, 200, $schema1],
            'teste cadastro realizado sem informar categoria' => [$data2, 200, $schema1],
            'teste cadastro duplicado no mês' => [$data3, 400, $schema2],
            'teste cadastro descrição inválida' => [$data4, 400, $schema3],
            'teste cadastro valor inválido' => [$data5, 400, $schema3],
            'teste cadastro valor negativo' => [$data6, 400, $schema3],
            'teste cadastro data inválida' => [$data7, 400, $schema3],
            'teste cadastro categoria inválida' => [$data8, 400, $schema3],
            'teste cadastro com campos faltando' => [$data9, 400, $schema3]

        ];
    }

    protected function getFacade(): AbstractContaContabilFacade
    {
        return self::getContainer()->get(DespesaFacade::class);
    }

    protected function getContaContabil(): AbstractContaContabil
    {
        $despesa = new Despesa();

        $despesa
            ->setCategoria('Alimentação')
            ->setDescricao('testeDespesa')
            ->setValor(2500)
            ->setData(new DateTime('2022-09-01'))
        ;

        return $despesa;

    }
}