<?php

namespace App\DataFixtures;

use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Facade\ReceitaFacade;

class ReceitaFixtures extends Fixture
{

    public function __construct(

        private ReceitaFacade $facade
    ) {
    }
    public function load(ObjectManager $manager): void
    {
        $receitas = $this->getReceitas();

        foreach ($receitas as $receita) {
            $manager->persist($receita);
        }

        $manager->flush();
    }

    private function getReceitas()
    {
        $receitas = [];

        // monta receitas Genericas em três meses diferentes
        $date_start = new DateTime('2022-01-01');
        $date_end = new DateTime('2022-03-01');

        for ($i = $date_start; $i <= $date_end; $i->modify('+1 month')) {

            for ($j = 1; $j <= 5; $j++) {

                $data = clone $i;
                $receita = $this->facade->createReceita("Receita{$j}", 2500, $data);
                $receitas[] = $receita;
            }
        }

        // monta receitas de Renda em três anos diferentes
        $date_start = new DateTime('2020-03-01');
        $date_end = new DateTime('2023-03-01');

        for ($i = $date_start; $i <= $date_end; $i->modify('+1 year')) {

            for ($j = 1; $j <= 5; $j++) {

                $data = clone $i;
                $receita = $this->facade->createReceita("Renda{$j}", 1000, $data);
                $receitas[] = $receita;
            }
        }

        return $receitas;
    }
}
