<?php

namespace App\Test\Application;

use App\Entity\AbstractContaContabil;
use App\Entity\User;
use App\Exception\EntityNotFoundException;
use App\Facade\AbstractContaContabilFacade;
use App\Trait\ClientAuthentication;
use App\Trait\SchemaValidation;
use JsonSchema\Validator;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractContaContabilControllerTest extends WebTestCase
{

    use SchemaValidation, ClientAuthentication;
    
    protected  KernelBrowser $client;
    protected  string $schemaPath = __DIR__ . '/../../../schemas/Response';
    protected string $jwt;

    protected string $endpoint;

    public function setUp(): void
    {
     
        self::ensureKernelShutdown();
        $this->client = self::createClient();

        $this->authenticateClient();

    }

    /**
     * @dataProvider createActionProvider
     */
    public function testCreateAction(array $data, int $expectedStatus, object $expectedSchema)
    {
        $crawler = $this->client->jsonRequest('POST', "/{$this->endpoint}", $data);

        $response = $this->client->getResponse();
        $responseBody = json_decode($response->getContent());

        self::assertResponseStatusCodeSame($expectedStatus);
        self::assertJsonSchema($expectedSchema, $responseBody);
    }

    /**
     * @dataProvider readAllActionProvider
     */
    public function testReadAllAction($search, $totalExpected, $schema)
    {
        $url = empty($search) ? $this->endpoint : $this->endpoint . '?descricao=' . $search;

        $crawler = $this->client->request('GET', $url);

        $response = $this->client->getResponse();
        $jsonResponse = json_decode($response->getContent(), true);

        self::assertResponseStatusCodeSame(200);
        self::assertCount($totalExpected, $jsonResponse);
        self::assertJsonSchema($schema, $jsonResponse);
    }

    public function testReadByIdAction()
    {
        $facade = $this->getFacade();
        $contaContabil = $this->getContaContabil();

        $facade->create($contaContabil);
        $id = $contaContabil->getId();

        $crawler = $this->client->request('GET', "/{$this->endpoint}/{$id}");

        $response = $this->client->getResponse();
        $jsonResponse = $response->getContent();

        self::assertResponseStatusCodeSame(200);
        self::assertSame(json_encode($contaContabil), $jsonResponse);
    }

    /**
     * @dataProvider readByDateActionProvider
     */
    public function testReadByDateAction($year, $month, $totalExpected, $schema)
    {
        $url = "/{$this->endpoint}" . "/{$year}/{$month}";

        $crawler = $this->client->request('GET', $url);

        $response = $this->client->getResponse();
        $jsonResponse = json_decode($response->getContent(), true);

        self::assertCount($totalExpected, $jsonResponse);
        self::assertJsonSchema($schema, $jsonResponse);
    }

    /**
     * @dataProvider updateActionProvider
     */
    public function testUpdateAction(array $data, int $expectedStatus, object $expectedSchema)
    {

        $facade = $this->getFacade();
        $contaContabil = $this->getContaContabil();

        $facade->create($contaContabil);
        $id = $contaContabil->getId();

        $crawler = $this->client->jsonRequest('PUT', "/{$this->endpoint}/{$id}", $data);

        $response = $this->client->getResponse();
        $responseBody = json_decode($response->getContent());

        self::assertResponseStatusCodeSame($expectedStatus);
        self::assertJsonSchema($expectedSchema, $responseBody);
    }

    public function testUpdateNotFoundAction()
    {
        $schema = json_decode(file_get_contents($this->schemaPath . '/Common/status_message.json'));

        $crawler = $this->client->request('PUT', "/{$this->endpoint}/-10");

        $response = $this->client->getResponse();
        $responseBody = json_decode($response->getContent());

        self::assertResponseStatusCodeSame(404);

        self::assertJsonSchema($schema, $responseBody);
    }

    public function testDeleteAction()
    {

        $this->expectException(EntityNotFoundException::class);

        $schema = json_decode(file_get_contents($this->schemaPath . '/Common/status_message.json'));
        $facade = $this->getFacade();
        $contaContabil = $this->getContaContabil();

        $facade->create($contaContabil);
        $id = $contaContabil->getId();

        $crawler = $this->client->request('DELETE', "/{$this->endpoint}/{$id}");
        $contaContabil = $facade->getEntityFromId($id);

        $response = $this->client->getResponse();
        $responseBody = json_decode($response->getContent());

        self::assertResponseStatusCodeSame(200);

        self::assertJsonSchema($schema, $responseBody);
    }

    public function testDeleteNotFoundAction()
    {
        $schema = json_decode(file_get_contents($this->schemaPath . '/Common/status_message.json'));

        $crawler = $this->client->request('DELETE', "/{$this->endpoint}/-10");

        $response = $this->client->getResponse();
        $responseBody = json_decode($response->getContent());

        self::assertResponseStatusCodeSame(404);

        self::assertJsonSchema($schema, $responseBody);
    }

    protected abstract function createActionProvider(): array;
    protected abstract function readAllActionProvider(): array;
    protected abstract function readByDateActionProvider(): array;
    protected abstract function updateActionProvider(): array;

    protected abstract function getFacade(): AbstractContaContabilFacade;
    protected abstract function getContaContabil(): AbstractContaContabil;

    
}
