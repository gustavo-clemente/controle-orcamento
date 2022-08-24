<?php

namespace App\Entity;

use App\Entity\AbstractContaContabil;
use App\Repository\ReceitaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReceitaRepository::class)]
class Receita extends AbstractContaContabil
{ 
    
}
