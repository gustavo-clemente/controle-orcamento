<?php

namespace App\Helper;

use App\Abstract\Helper\ContaContabilFactory;
use App\Entity\Receita;
use DateTime;

class ReceitaFactory extends ContaContabilFactory
{
    public function create(string $payload): Receita|array
    {
        $json = json_decode($payload, true);
        
        if($violations = $this->validate($json)){

            return $violations;
        }

        $date = new DateTime($json['data']);

        $receita = new Receita();
        $receita->setDescricao($json['descricao'])->setValor($json['valor'])->setData($date);

        return $receita;
    }

}
