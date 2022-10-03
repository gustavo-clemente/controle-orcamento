<?php

namespace App\Test\Application\Controller;

use App\Trait\SchemaValidation;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase
{

    use SchemaValidation;

    private KernelBrowser $client;
    protected  string $schemaPath = __DIR__ . '/../../../schemas/Response';

    public function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = self::createClient();
    }

    /**
     * @dataProvider loginActionProvider
     */
    public function testLoginAction(array $data, int $expectedStatus, object $expectedSchema)
    {
        $crawler = $this->client->jsonRequest('POST', '/login', $data);

        $response = $this->client->getResponse();
        $responseBody = json_decode($response->getContent());

        self::assertResponseStatusCodeSame($expectedStatus);
        self::assertJsonSchema($expectedSchema, $responseBody);
    }

    private function loginActionProvider()
    {
        $data1 = [

            'usuario' => 'usuario_testes',
            'senha' => '123@456'
        ];

        $data2 = [

            'usuario' => 'usuario_testes',
            'senha' => '123456'
        ];

        $data3 = [

            'usuario' => 'usuario_invalido',
            'senha' => '123456'
        ];

        $data4 = [

            'senha' => '123456'
        ];

        $data5 = [

            'usuario' => 'usuario_testes'
        ];

        $schema1 = json_decode(file_get_contents($this->schemaPath . '/Login/login_ok.json'));
        $schema2 = json_decode(file_get_contents($this->schemaPath . '/Common/status_message.json'));
        $schema3 = json_decode(file_get_contents($this->schemaPath . '/Common/errors_message.json'));

        return [

            'teste login autenticado' => [$data1, 200, $schema1],
            'teste login senha inválida' => [$data2, 401, $schema2],
            'teste login usuário inválido' => [$data3, 401, $schema2]
            // 'teste login sem usuário' => [$data4, 400, $schema3],
            // 'teste login sem senha' => [$data5, 400, $schema3],
        ];
    }
}
