<?php

namespace App\Test\Integration\Repository;

use App\Entity\Despesa;
use App\Facade\DespesaFacade;
use App\Repository\DespesaRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use function PHPUnit\Framework\assertContainsOnlyInstancesOf;

class DespesaRepositoryTest extends KernelTestCase
{
    private DespesaRepository $repository;

    public function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        $this->repository = $container->get(DespesaRepository::class);
    }

    /**
     * @dataProvider findByDescriptionProvider
     */
    public function testFindByDescription(string $search, int $expectedCount)
    {
        $result = $this->repository->findByDescription($search);

        self::assertCount($expectedCount, $result);
        self::assertContainsOnlyInstancesOf(Despesa::class, $result);
    }

    /**
     * @dataProvider findMonthDescriptionProvider
     */
    public function testFindMonthDescription(Despesa $despesa, $expectedResult)
    {
        $result = !is_null($this->repository->findMonthDescription($despesa));

        self::assertEquals($expectedResult, $result);
    }

    /**
     * @dataProvider findByDateProvider
     */
    public function testFindByDate(int $year, int $month, int $expectedCount)
    {
        $result = $this->repository->findByDate($year, $month);

        self::assertCount($expectedCount, $result);
        self::assertContainsOnlyInstancesOf(Despesa::class, $result);
    }

    /**
     * @dataProvider getTotalMonthProvider
     */
    public function testGetTotalMonth(int $year, int $month, int $expectedtotal)
    {
        $total = $this->repository->getTotalMonth($year, $month);

        self::assertEquals($expectedtotal, $total);
    }

    /**
     * @dataProvider getTotalPerCategoryProvider
     */
    public function testGetTotalPerCategory(int $year, int $month, $expectedResult)
    {
        $result = $this->repository->getTotalPerCategory($year, $month);

        self::assertEquals($expectedResult, $result);
    }

    private function findByDescriptionProvider()
    {
        return [

            "busca exata" => ['Despesa1', 3],
            "busca aproximada" => ['Conta', 20],
            "busca vázia" => ['', 50],
            "busca registro inexistente" => ['busca qualquer', 0]
        ];
    }

    private function findMonthDescriptionProvider()
    {
        $container = self::getContainer();
        /** @var DespesaFacade  */
        $facade = $container->get(DespesaFacade::class);

        $despesa1 = $facade->createDespesa('Conta1', 230, new DateTime('2020-03-10'), 'Moradia');
        $despesa2 = $facade->createDespesa('ContaDiferente', 230, new DateTime('2020-03-10'), 'Moradia');
        $despesa3 = $facade->createDespesa('Conta1', 230, new DateTime('2019-03-01'), 'Moradia');
        $despesa4 = $facade->createDespesa('ContaDiferente', 230, new DateTime('2020-06-01'), 'Moradia');
        $despesa5 = $facade->createDespesa('ContaDiferente', 230, new DateTime('2019-06-01'), 'Moradia');

        return [

            'Despesa com mesmo nome no mesmo mês do mesmo ano' => [$despesa1, true],
            'Despesa com nome diferente no mesmo mês do mesmo ano' => [$despesa2, false],
            'Despesa com mesmo nome no mesmo mês mas em ano diferente' => [$despesa3, false],
            'Despesa com outro nome em outro mês no mesmo ano' => [$despesa4, false],
            'Despesa com outro nome em outro mês em outro ano' => [$despesa5, false]
        ];
    }

    private function findByDateProvider()
    {
        return [

            'Janeiro de 2022' => [2022, 1, 5],
            'Março de 2020' => [2020, 3, 5],
            'Sem Resultado' => [2019, 3, 0]
        ];
    }

    private function getTotalMonthProvider()
    {
        return [

            'Janeiro de 2022' => [2022, 1, 12500],
            'Março de 2020' => [2020, 3, 1500],
            'Sem Resultado' => [2019, 3, 0]
        ];
    }

    private function getTotalPerCategoryProvider()
    {
        $result1 = [

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
        ];

        $result2 = [

            [
                'categoria' => 'Alimentação',
                'total' => 12500
            ]
        ];

        $result3 = [];
        return [

            'Janeiro de 2024' => [2024, 1, $result1],
            'Fevereiro de 2022' => [2022, 2, $result2],
            'Sem resultado' => [2019, 12, $result3]
        ];
    }
}
