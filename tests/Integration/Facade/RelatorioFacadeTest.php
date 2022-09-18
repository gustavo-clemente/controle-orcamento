<?php

namespace App\Test\Integration\Facade;

use App\Facade\RelatorioFacade;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RelatorioFacadeTest extends KernelTestCase
{
    private RelatorioFacade $facade;

    public function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        $this->facade = $container->get(RelatorioFacade::class);
    }

    /**
     * @dataProvider  getSumaryProvider
     */
    public function testGetSumary(int $year, int $month, $expectedReturn)
    {
        $result = $this->facade->getSumary($year, $month);

        self::assertEquals($expectedReturn['totalReceita'], $result['totalReceita']);
        self::assertEquals($expectedReturn['totalDespesa'], $result['totalDespesa']);
        self::assertEquals($expectedReturn['saldoFinal'], $result['saldoFinal']);
        self::assertEquals($expectedReturn['totalCategoria'], $result['totalCategoria']);
    }

    private function getSumaryProvider()
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
            'totalCategoria' => [
            ]
        ];

        return [

            'Janeiro de 2022' => [2022, 1, $result1],
            'Março de 2020' => [2020, 3, $result2],
            'Janeiro de 2024' => [2024, 1, $result3],
            'Sem resultados' => [2019, 6, $result4]
        ];
    }
}
