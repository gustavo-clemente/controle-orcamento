<?php

namespace App\Helper;

use App\Entity\Receita;
use DateTime;
use Exception;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

class ReceitaFactory
{
    public function create(string $payload): Receita|array
    {
        $json = json_decode($payload, true);
        $violations = $this->validate($json);

        if (count($violations) > 0) {

            $arrayViolations = $this->createViolationsArray($violations);
            return $arrayViolations;
        }

        $date = new DateTime($json['data']);

        $receita = new Receita();
        $receita->setDescricao($json['descricao'])->setValor($json['valor'])->setData($date);

        return $receita;
    }

    private function validate(array $json)
    {
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

        return $violations;
    }

    private function createViolationsArray(ConstraintViolationListInterface $violationList)
    {
        $arrayViolations = [];
        foreach ($violationList as $violation) {

            $arrayViolations[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return $arrayViolations;
    }
}
