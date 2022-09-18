<?php

namespace App\Trait;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;

trait ControllerHelpers
{
    private function getJson(Request $request)
    {
        $requestBody = $request->getContent();
        $data = json_decode($requestBody, true);

        return !is_null($data) ? $data : [];
    }

    private function getErrorMessages(Form $form)
    {
        $errors = array();

        foreach ($form->getErrors() as $key => $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->getErrorMessages($child);
            }
        }

        return $errors;
    }
}