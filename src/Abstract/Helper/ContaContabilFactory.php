<?php

namespace App\Abstract\Helper;

use App\Trait\CreateViolationsArray;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

abstract class ContaContabilFactory
{
    use CreateViolationsArray;

    protected function validate(?array $json): array|false
    {
        $json = is_null($json) ? [] : $json;
        $validator = Validation::createValidator();
        $constraints = new Assert\Collection([
            'fields' => [

                'descricao' => new Assert\NotBlank([
                    "message" => "O campo não pode ser vázio"
                ]),
                'valor' => [
                    new Assert\Type([
                        "type" => ["int","float"],
                        "message" => "O valor deve ser um número decimal"
                    ]),
                    new Assert\Positive([
                        "message" => "O valor deve ser um número maior do que 0"
                    ])
                ],
                'data' => new Assert\Date([
                    "message" => "A data informada é invalida. O formato deve ser YYYY-MM-DD"
                ])
            ],
            'missingFieldsMessage' => 'O campo é obrigatorio'


        ]);

        $violations = $validator->validate($json, $constraints);
        $arrayViolations = $this->createViolationsArray($violations);

        return count($arrayViolations) > 0 ? $arrayViolations : false;
    }
}