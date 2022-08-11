<?php

namespace App\Trait;

use Symfony\Component\Validator\ConstraintViolationListInterface;


trait CreateViolationsArray
{
    protected function createViolationsArray(ConstraintViolationListInterface $violationList)
    {
        $arrayViolations = [];
        foreach ($violationList as $violation) {

            $arrayViolations[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return $arrayViolations;
    }
}