<?php

namespace App\Trait;

trait ClientAuthentication
{
    protected function authenticateClient()
    {
        $this->client->jsonRequest('POST','/login',[

            'usuario' => 'usuario_testes',
            'senha' => '123@456'
        ]);

        $data = json_decode($this->client->getResponse()->getContent(),true);

        $this->client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));
    }
}