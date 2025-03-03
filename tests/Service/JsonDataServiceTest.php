<?php

namespace App\Tests\Service;

use App\Service\JsonDataService;
use App\Entity\Reserva;
use Symfony\Component\HttpFoundation\Response;
use PHPUnit\Framework\TestCase;

class JsonDataServiceTest extends TestCase
{

    public function testConvertirDatosAJson_RetornaJsonCorrecto()
    {
        // Crear mocks de Reserva
        $reserva1 = $this->createMock(Reserva::class);
        $reserva1->method('convertirEnArray')->willReturn(
          [
            'localizador' => 34637,
            'huesped' => 'Nombre 1',
            'fechaEntrada' => '2018-10-04',
            'fechaSalida' => '2018-10-05',
            'hotel' => 'Hotel 1',
            'precio' => 112.85,
            'posiblesAcciones' => 'Cobrar Devolver',
          ]
        );

        $reserva2 = $this->createMock(Reserva::class);
        $reserva2->method('convertirEnArray')->willReturn(
          [
            'localizador' => 34638,
            'huesped' => 'Nombre 2',
            'fechaEntrada' => '2019-10-04',
            'fechaSalida' => '2019-10-05',
            'hotel' => 'Hotel 2',
            'precio' => 250.85,
            'posiblesAcciones' => 'Cobrar Devolver',
          ]
        );

        $reservas = [$reserva1, $reserva2];

        $expectedJson = json_encode(
          [
            [
              'localizador' => 34637,
              'huesped' => 'Nombre 1',
              'fechaEntrada' => '2018-10-04',
              'fechaSalida' => '2018-10-05',
              'hotel' => 'Hotel 1',
              'precio' => 112.85,
              'posiblesAcciones' => 'Cobrar Devolver',
            ],
            [
              'localizador' => 34638,
              'huesped' => 'Nombre 2',
              'fechaEntrada' => '2019-10-04',
              'fechaSalida' => '2019-10-05',
              'hotel' => 'Hotel 2',
              'precio' => 250.85,
              'posiblesAcciones' => 'Cobrar Devolver',
            ],
          ],
          JSON_UNESCAPED_UNICODE
        );

        $service = new JsonDataService();
        $result = $service->convertirDatosAJson($reservas);

        $this->assertSame($expectedJson, $result);
    }

    public function testGenerarDescargaJson_RetornaResponseConHeaders()
    {
        $contenido = '[{"localizador":34637,"huesped":"Nombre 1","fechaEntrada":"2018-10-04","fechaSalida":"2018-10-04","hotel":"Hotel 4","precio":112.49,"posiblesAcciones":"Cobrar Devolver"},{"localizador":34672,"huesped":"Nombre 31","fechaEntrada":"2018-10-04","fechaSalida":"2018-10-04","hotel":"Hotel 5","precio":63.57,"posiblesAcciones":"Cobrar Devolver"}]';
        $nombreArchivo = 'test';
        $service = new JsonDataService();

        $response = $service->generarDescargaJson($contenido, $nombreArchivo);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame($contenido, $response->getContent());
        $this->assertSame(
          'application/json',
          $response->headers->get('Content-Type')
        );
        $this->assertMatchesRegularExpression(
          '/attachment; filename="'.preg_quote($nombreArchivo).'-\d+\.json"/',
          $response->headers->get('Content-Disposition')
        );
    }

}