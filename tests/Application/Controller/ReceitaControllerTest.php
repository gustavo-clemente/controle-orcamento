<?php

namespace App\Test\Application\Controller;

use App\Entity\AbstractContaContabil;
use App\Entity\Receita;
use App\Facade\AbstractContaContabilFacade;
use App\Facade\ReceitaFacade;
use App\Test\Application\AbstractContaContabilControllerTest;
use DateTime;

class ReceitaControllerTest extends AbstractContaContabilControllerTest
{
    protected string $endpoint = 'receita';

    protected function createActionProvider(): array
    {
        $data1 = [

            'descricao' => 'receitaTeste',
            'valor' => 1200,
            'data' => '2022-09-01'
        ];

        $data2 = [

            'descricao' => 'Receita1',
            'valor' => 1200,
            'data' => '2022-01-01'
        ];

        $data3 = [

            'descricao' => '',
            'valor' => 1200,
            'data' => '2022-01-01'
        ];

        $data4 = [

            'descricao' => 'teste',
            'valor' => "abcde",
            'data' => '2022-01-01'
        ];

        $data5 = [

            'descricao' => 'teste',
            'valor' => -1200,
            'data' => '2022-01-01'
        ];

        $data6 = [

            'descricao' => 'teste',
            'valor' => 1200,
            'data' => 'sasasasass'
        ];

        $data7 = [

            'descricao' => 'teste'
        ];

        $schema1 = json_decode(file_get_contents($this->schemaPath . '/Receita/created.json'));
        $schema2 = json_decode(file_get_contents($this->schemaPath . '/Common/status_message.json'));
        $schema3 = json_decode(file_get_contents($this->schemaPath . '/Receita/invalid_request.json'));


        return [

            'teste cadastro realizado' => [$data1, 201, $schema1],
            'teste cadastro duplicado no mês' => [$data2, 400, $schema2],
            'teste cadastro descrição inválida' => [$data3, 400, $schema3],
            'teste cadastro valor inválido' => [$data4, 400, $schema3],
            'teste cadastro valor negativo' => [$data5, 400, $schema3],
            'teste cadastro data inválida' => [$data6, 400, $schema3],
            'teste cadastro com campos faltando' => [$data7, 400, $schema3]

        ];
    }

    protected function readAllActionProvider(): array
    {
        $schema = json_decode(file_get_contents($this->schemaPath . '/Receita/list.json'));

        return [

            'busca geral' => ['', 35, $schema],
            'busca aproximada' => ['Receita', 15, $schema],
            'busca específica' => ['renda1', 4, $schema],
            'busca inválida' => ['sem correspondencia', 0, $schema]
        ];
    }

    protected function readByDateActionProvider(): array
    {
        $schema = json_decode(file_get_contents($this->schemaPath . '/Receita/list.json'));

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

            'descricao' => 'receitaTeste',
            'valor' => 1200,
            'data' => '2022-09-01'
        ];

        $data2 = [

            'descricao' => 'Receita1',
            'valor' => 1200,
            'data' => '2022-01-01'
        ];

        $data3 = [

            'descricao' => '',
            'valor' => 1200,
            'data' => '2022-01-01'
        ];

        $data4 = [

            'descricao' => 'teste',
            'valor' => "abcde",
            'data' => '2022-01-01'
        ];

        $data5 = [

            'descricao' => 'teste',
            'valor' => -1200,
            'data' => '2022-01-01'
        ];

        $data6 = [

            'descricao' => 'teste',
            'valor' => 1200,
            'data' => 'sasasasass'
        ];

        $data7 = [

            'descricao' => 'teste'
        ];

        $schema1 = json_decode(file_get_contents($this->schemaPath . '/Receita/created.json'));
        $schema2 = json_decode(file_get_contents($this->schemaPath . '/Common/status_message.json'));
        $schema3 = json_decode(file_get_contents($this->schemaPath . '/Receita/invalid_request.json'));


        return [

            'teste cadastro realizado' => [$data1, 200, $schema1],
            'teste cadastro duplicado no mês' => [$data2, 400, $schema2],
            'teste cadastro descrição inválida' => [$data3, 400, $schema3],
            'teste cadastro valor inválido' => [$data4, 400, $schema3],
            'teste cadastro valor negativo' => [$data5, 400, $schema3],
            'teste cadastro data inválida' => [$data6, 400, $schema3],
            'teste cadastro com campos faltando' => [$data7, 400, $schema3]

        ];
    }

    protected function getFacade(): AbstractContaContabilFacade
    {
        return self::getContainer()->get(ReceitaFacade::class);
    }

    protected function getContaContabil(): AbstractContaContabil
    {
        $receita = new Receita();

        $receita
            ->setDescricao('testeReceita')
            ->setValor(1200)
            ->setData(new DateTime('2022-09-01'))
        ;

        return $receita;
    }
}
