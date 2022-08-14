<?php

namespace App\Entity;

use App\Entity\ContaContabil;
use App\Repository\DespesaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DespesaRepository::class)]
class Despesa extends ContaContabil
{
    #[ORM\Column()]
    private string $categoria = 'Outras';

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
