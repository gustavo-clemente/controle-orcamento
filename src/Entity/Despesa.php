<?php

namespace App\Entity;

use App\Abstract\Entity\ContaContabil;
use App\Repository\DespesaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DespesaRepository::class)]
class Despesa extends ContaContabil
{
}
