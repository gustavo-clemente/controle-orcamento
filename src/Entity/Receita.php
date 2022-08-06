<?php

namespace App\Entity;

use App\Abstract\Entity\ContaContabil;
use App\Repository\ReceitaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReceitaRepository::class)]
class Receita extends ContaContabil
{  
}
