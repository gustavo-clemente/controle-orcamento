<?php

namespace App\Request;

use App\Entity\Receita;
use Symfony\Component\Validator\Constraints as Assert;

class ReceitaRequest
{
    public $descricao;

    public $valor;

    public $data;

    public static function createFrom(Receita $receita): self
    {
        $receitaRequest = new self();

        $receitaRequest->descricao = $receita->getDescricao();
        $receitaRequest->valor = $receita->getValor();
        $receitaRequest->data = $receita->getData();

        return $receitaRequest;
    }
}