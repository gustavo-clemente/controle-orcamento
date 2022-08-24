<?php

namespace App\Entity;

use App\Entity\AbstractContaContabil;
use App\Repository\DespesaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DespesaRepository::class)]
class Despesa extends AbstractContaContabil
{
    #[ORM\Column()]
    private string $categoria;

    public function getCategoria(): string
    {
        return $this->categoria;
    }

    public function setCategoria(string $categoria): self
    {
        $this->categoria = $categoria;

        return $this;
    }

    public function jsonSerialize(): mixed
    {
        $data = parent::jsonSerialize();
        $data['categoria'] = $this->getCategoria();

        return $data;
    }

    
}
