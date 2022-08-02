<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
class Receita
{
    #[ORM\Column(), ORM\Id, ORM\GeneratedValue()]
    private int $id;

    #[ORM\Column()]
    private string $descricao;

    #[ORM\Column()]
    private float $valor;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private \DateTimeInterface $data;

    public function getDescricao(): string
    {
        return $this->descricao;
    }

    public function setDescricao(string $descricao): self
    {
        $this->descricao = $descricao;

        return $this;
    }

    public function getValor(): float
    {
        return $this->valor;
    }

    public function setValor(float $valor): self
    {
        $this->valor = $valor;

        return $this;
    }

    public function getData(): \DateTimeInterface
    {
        return $this->data;
    }

    public function setData(\DateTimeInterface $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

}
