<?php

namespace App\Test\Integration\Repository;

use App\Entity\Receita;
use App\Facade\ReceitaFacade;
use App\Repository\ReceitaRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ReceitaRepositoryTest extends KernelTestCase
{
    private ReceitaRepository $repository;

    public function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        $this->repository = $container->get(ReceitaRepository::class);
    }

    /**
     * @dataProvider findByDescriptionProvider
     */
    public function testFindByDescription(string $search, int $expectedCount)
    {
        $result = $this->repository->findByDescription($search);

        self::assertCount($expectedCount, $result);
        self::assertContainsOnlyInstancesOf(Receita::class, $result);
    }

    /**
     * @dataProvider findMonthDescriptionProvider
     */
    public function testFindMonthDescription(Receita $receita, $expectedResult)
    {
        $result = !is_null($this->repository->findMonthDescription($receita));

        self::assertEquals($expectedResult, $result);
    }

    /**
     * @dataProvider findByDateProvider
     */
    public function testFindByDate(int $year, int $month, int $expectedCount)
    {
        $result = $this->repository->findByDate($year, $month);

        self::assertCount($expectedCount, $result);
        self::assertContainsOnlyInstancesOf(Receita::class, $result);
    }

    /**
     * @dataProvider getTotalMonthProvider
     */
    public function testGetTotalMonth(int $year, int $month, int $expectedtotal)
    {
        $total = $this->repository->getTotalMonth($year, $month);

        self::assertEquals($expectedtotal, $total);
    }

    private function findByDescriptionProvider()
    {
        return [

            "busca exata" => ['Receita1', 3],
            "busca aproximada" => ['Renda', 20],
            "busca vázia" => ['', 35],
            "busca registro inexistente" => ['busca qualquer', 0]
        ];
    }

    private function findMonthDescriptionProvider()
    {
        $container = self::getContainer();
        /** @var ReceitaFacade  */
        $facade = $container->get(ReceitaFacade::class);

        $receita1 = $facade->createReceita('Renda1', 230, new DateTime('2020-03-10'));
        $receita2 = $facade->createReceita('ReceitaDiferente', 230, new DateTime('2020-03-10'), 'Moradia');
        $receita3 = $facade->createReceita('Receita1', 230, new DateTime('2019-03-01'), 'Moradia');
        $receita4 = $facade->createReceita('ReceitaDiferente', 230, new DateTime('2020-06-01'), 'Moradia');
        $receita5 = $facade->createReceita('ReceitaDiferente', 230, new DateTime('2019-06-01'), 'Moradia');

        return [

            'Receita com mesmo nome no mesmo mês do mesmo ano' => [$receita1, true],
            'Receita com nome diferente no mesmo mês do mesmo ano' => [$receita2, false],
            'Receita com mesmo nome no mesmo mês mas em ano diferente' => [$receita3, false],
            'Receita com outro nome em outro mês no mesmo ano' => [$receita4, false],
            'Receita com outro nome em outro mês em outro ano' => [$receita5, false]
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
            'Março de 2020' => [2020, 3, 5000],
            'Sem Resultado' => [2019, 3, 0]
        ];
    }

}