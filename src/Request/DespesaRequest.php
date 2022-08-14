<?php

namespace App\Request;

use App\Entity\Despesa;

class DespesaRequest
{
    public $descricao;

    public $valor;

    public $data;

    public $categoria;

    public static function createFrom(Despesa $despesa): self
    {
        $despesaRequest = new self();

        $despesaRequest->descricao = $despesa->getDescricao();
        $despesaRequest->valor = $despesa->getValor();
        $despesaRequest->data = $despesa->getData();
        $despesaRequest->categoria = $despesa->getCategoria();

        return $despesaRequest;
    }
}