<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ResumoControllerTest extends WebTestCase
{
    protected  KernelBrowser $client;
    protected  string $schemaPath = __DIR__ . '/../../../schemas/Response/Summary';

    public function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = self::createClient();
    }

    /**
     * @dataProvider readSummaryActionProvider
     */
    public function testReadSummaryAction($year, $month, $expectedReturn)
    {
        $crawler = $this->client->request('GET', "/resumo/{$year}/{$month}");

        $response = $this->client->getResponse();
        $responseBody = json_decode($response->getContent(), true);

        self::assertEquals($expectedReturn['totalReceita'], $responseBody['totalReceita']);
        self::assertEquals($expectedReturn['totalDespesa'], $responseBody['totalDespesa']);
        self::assertEquals($expectedReturn['saldoFinal'], $responseBody['saldoFinal']);
        self::assertEquals($expectedReturn['totalCategoria'], $responseBody['totalCategoria']);
    }

    private function readSummaryActionProvider()
    {
        $result1 = [

            'totalReceita' => 12500,
            'totalDespesa' => 12500,
            'saldoFinal' => 0,
            'totalCategoria' => [
                [
                    'categoria' => 'Alimentação',
                    'total' => 12500
                ]
            ]
        ];

        $result2 = [

            'totalReceita' => 5000,
            'totalDespesa' => 1500,
            'saldoFinal' => 3500,
            'totalCategoria' => [
                [
                    'categoria' => 'Moradia',
                    'total' => 1500
                ]
            ]
        ];


        $result3 = [

            'totalReceita' => 0,
            'totalDespesa' => 1500,
            'saldoFinal' => -1500,
            'totalCategoria' => [
                [
                    'categoria' => 'Moradia',
                    'total' => 500
                ],

                [
                    'categoria' => 'Sáude',
                    'total' => 500
                ],

                [
                    'categoria' => 'Transporte',
                    'total' => 500
                ]
            ]
        ];

        $result4 = [

            'totalReceita' => 0,
            'totalDespesa' => 0,
            'saldoFinal' => 0,
            'totalCategoria' => []
        ];

        return [

            'Janeiro de 2022' => [2022, 1, $result1],
            'Março de 2020' => [2020, 3, $result2],
            'Janeiro de 2024' => [2024, 1, $result3],
            'Sem resultados' => [2019, 6, $result4]
        ];
    }
}
