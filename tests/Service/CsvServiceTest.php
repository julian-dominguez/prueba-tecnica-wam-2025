<?php

namespace App\Tests\Service;

use App\Service\CsvService;
use App\Exception\ServicioCsvException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use PHPUnit\Framework\TestCase;

class CsvServiceTest extends TestCase
{

    private $httpClient;

    private $endpoint = 'http://test.endpoint.com';

    private $user = 'test_user';

    private $password = 'test_pass';

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClientInterface::class);
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \App\Exception\ServicioCsvException
     */
    public function testObtenerDatosCsv_Correctos()
    {
        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->method('getStatusCode')->willReturn(200);
        $mockResponse->method('getContent')->willReturn('csv_content');

        // Configurar el cliente HTTP para devolver la respuesta mockeada
        $this->httpClient->expects($this->once())
          ->method('request')
          ->with(
            'GET',
            $this->endpoint,
            ['auth_basic' => [$this->user, $this->password]]
          )
          ->willReturn($mockResponse);

        // Crear instancia del servicio
        $csvService = new CsvService(
          $this->httpClient,
          $this->endpoint,
          $this->user,
          $this->password
        );

        $result = $csvService->obtenerDatosCsv();

        $this->assertSame('csv_content', $result);
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     */
    public function testObtenerDatosCsv_ServicioCsvException(
    )
    {
        // Crear mock de respuesta con código de estado 500
        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->method('getStatusCode')->willReturn(500);

        // Configurar el cliente HTTP
        $this->httpClient->expects($this->once())
          ->method('request')
          ->with(
            'GET',
            $this->endpoint,
            ['auth_basic' => [$this->user, $this->password]]
          )
          ->willReturn($mockResponse);

        // Crear instancia del servicio
        $csvService = new CsvService(
          $this->httpClient,
          $this->endpoint,
          $this->user,
          $this->password
        );

        // Verificar que se lanza la excepción correcta
        $this->expectException(ServicioCsvException::class);
        $this->expectExceptionMessage(
          "Error al conectar con el servidor que provee el CSV"
        );
        $this->expectExceptionCode(500);

        $csvService->obtenerDatosCsv();
    }

}