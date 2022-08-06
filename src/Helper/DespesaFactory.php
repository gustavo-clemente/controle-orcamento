<?php

namespace App\Helper;

use App\Abstract\Helper\ContaContabilFactory;
use App\Entity\Despesa;
use DateTime;

class DespesaFactory extends ContaContabilFactory
{
    public function create(string $payload): Despesa|array
    {
        $json = json_decode($payload, true);
        
        if($violations = $this->validate($json)){

            return $violations;
        }

        $date = new DateTime($json['data']);

        $receita = new Despesa();
        $receita->setDescricao($json['descricao'])->setValor($json['valor'])->setData($date);

        return $receita;
    }

}
