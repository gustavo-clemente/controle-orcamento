<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Facade\DespesaFacade;
use DateTime;

class DespesaFixtures extends Fixture
{

    public function __construct(
        private DespesaFacade $facade
    ) {
    }
    public function load(ObjectManager $manager): void
    {
        $despesas = $this->getDespesas();

        foreach($despesas as $despesa){
            $manager->persist($despesa);
        }

        $manager->flush();
    }

    private function getDespesas()
    {
        $despesas = [];

        // monta despesas Genericas em três meses diferentes
        $date_start = new DateTime('2022-01-01');
        $date_end = new DateTime('2022-03-01');

        for($i = $date_start; $i <= $date_end; $i->modify('+1 month')){

            for ($j = 1; $j <= 5; $j++) {

                $data = clone $i;
                $despesa = $this->facade->createDespesa("Despesa{$j}", 2500, $data, 'Alimentação');
                $despesas[] = $despesa;
            }
        }

        // monta despesas de Contas em três anos diferentes
        $date_start = new DateTime('2020-03-01');
        $date_end = new DateTime('2023-03-01');

        for($i = $date_start; $i <= $date_end; $i->modify('+1 year')){

            for ($j = 1; $j <= 5; $j++) {

                $data = clone $i;
                $despesa = $this->facade->createDespesa("Conta{$j}", 300, $data, 'Moradia');
                $despesas[] = $despesa;
            }
        }

        // monta despesas de três categorias diferentes
        $categorias = [

            'Sáude',
            'Moradia',
            'Transporte'
        ];

        foreach($categorias as $categoria){

            for($i = 1; $i <= 5; $i++){

                $despesa = $this->facade->createDespesa("$categoria{$i}", 100, new DateTime('2024-01-01'), $categoria);
                $despesas[] = $despesa;
            }
        }

        return $despesas;
    }
}
