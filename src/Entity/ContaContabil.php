<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JsonSerializable;

abstract class ContaContabil implements JsonSerializable
{

    #[ORM\Column(), ORM\Id, ORM\GeneratedValue()]
    protected ?int $id;

    #[ORM\Column()]
    #[Assert\NotBlank(message: "O campo não pode ser vázio")]
    protected ?string $descricao;

    #[ORM\Column()]
    #[Assert\NotBlank(message: "O campo não pode ser vázio")]
    #[Assert\Type(type: ['int', 'float','string'], message: "O valor deve ser um número decimal")]
    #[Assert\Positive(message: "O valor deve ser um número maior do que 0")]
    protected ?float $valor;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotNull(message: "O campo não pode ser vázio")]
    protected \DateTimeInterface $data;

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(?string $descricao): self
    {
        $this->descricao = $descricao;

        return $this;
    }

    public function getValor(): float
    {
        return $this->valor;
    }

    public function setValor(?float $valor): self
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

    public function jsonSerialize(): mixed
    {
        return [

            'id' => $this->getId(),
            'descricao' => $this->getDescricao(),
            'valor' => $this->getValor(),
            'data' => $this->getData()->format('Y-m-d')
        ];
    }
}
