<?php

namespace App\Tests\Service;

use App\Service\ReservaService;
use App\Service\CsvService;
use App\Service\JsonDataService;
use App\Entity\Reserva;
use PHPUnit\Framework\TestCase;

class ReservaServiceTest extends TestCase
{
    private $csvServiceMock;
    private $jsonDataServiceMock;
    private $reservaService;

    protected function setUp(): void
    {
        $this->csvServiceMock = $this->createMock(CsvService::class);

        $this->jsonDataServiceMock = $this->createMock(JsonDataService::class);

        // Crear instancia del servicio a probar
        $this->reservaService = new ReservaService(
          $this->csvServiceMock,
          $this->jsonDataServiceMock
        );
    }

    /**
     * @throws \DateMalformedStringException
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \App\Exception\ServicioCsvException
     */
    public function testObtenerReservas_RetornaListaDeReservas()
    {
        // Datos simulados del CSV
        $csvData = "34637;Nombre 1;2018-10-04;2018-10-05;Hotel 4;112.49;Cobrar Devolver\n
        34694;Nombre 2;2018-06-15;2018-06-17;Hotel 1;427.77;Cobrar Devolver\n
        34549;Nombre 3;2018-06-22;2018-06-27;Hotel 4;1029.95;Cobrar Devolver";
        $reservasProcesadas = [
          $this->createMock(Reserva::class),
          $this->createMock(Reserva::class),
        ];

        $this->csvServiceMock->expects($this->once())
          ->method('obtenerDatosCsv')
          ->willReturn($csvData);

        $this->csvServiceMock->expects($this->once())
          ->method('procesarDatosCsv')
          ->with($csvData)
          ->willReturn($reservasProcesadas);

        $result = $this->reservaService->obtenerReservas();

        $this->assertSame($reservasProcesadas, $result);
    }

    /**
     * @throws \DateMalformedStringException
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \App\Exception\ServicioCsvException
     */
    public function testBuscarReservas_ConTextoVacio_RetornaTodasLasReservas()
    {
        // Simular reservas obtenidas
        $reservas = [
          $this->createMock(Reserva::class),
          $this->createMock(Reserva::class),
        ];

        $this->csvServiceMock->expects($this->once())
          ->method('obtenerDatosCsv')
          ->willReturn("34694;Nombre 2;2018-06-15;2018-06-17;Hotel 1;427.77;Cobrar Devolver\n
          34549;Nombre 3;2018-06-22;2018-06-27;Hotel 4;1029.95;Cobrar Devolver");

        $this->csvServiceMock->expects($this->once())
          ->method('procesarDatosCsv')
          ->willReturn($reservas);

        $result = $this->reservaService->buscarReservas('');

        $this->assertSame($reservas, $result);
    }

    /**
     * @throws \DateMalformedStringException
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \App\Exception\ServicioCsvException
     */
    public function testBuscarReservas_ConTextoFiltro_RetornaReservasFiltradas()
    {
        // Simular reservas obtenidas
        $reserva1 = $this->createMock(Reserva::class);
        $reserva1->method('getContieneBusqueda')->willReturn(false);

        $reserva2 = $this->createMock(Reserva::class);
        $reserva2->method('getContieneBusqueda')->willReturn(true);

        $reservas = [$reserva1, $reserva2];

        $this->csvServiceMock->expects($this->once())
          ->method('obtenerDatosCsv')
          ->willReturn("34694;Nombre 2;2018-06-15;2018-06-17;Hotel 1;427.77;Cobrar Devolver\n
          34549;Nombre 3;2018-06-22;2018-06-27;Hotel 4;1029.95;Cobrar Devolver");

        $this->csvServiceMock->expects($this->once())
          ->method('procesarDatosCsv')
          ->willReturn($reservas);

        $result = $this->reservaService->buscarReservas('34694');

        $this->assertCount(1, $result);
        $this->assertSame([$reserva2], array_values($result));
    }

}