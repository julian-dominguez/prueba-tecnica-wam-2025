<?php

namespace App\Service;

use App\Entity\Reserva;
use Symfony\Component\HttpFoundation\Response;

class ReservaService
{

    public function __construct(
      private readonly CsvService $csvService,
      private readonly JsonDataService $jsonDataService,
    ) {}

    /**
     * Función que retorna una lista de objetos de tipo Reserva
     *
     * @return array
     * @throws \App\Exception\ServicioCsvException
     * @throws \DateMalformedStringException
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function obtenerReservas(): array
    {
        $reservasArray = $this->csvService->obtenerDatosCsv();

        return $this->csvService->procesarDatosCsv($reservasArray);
    }

    /**
     * Función que retorna las reservas a partir de un valor de búsqueda
     *
     * @param  string  $texto
     *
     * @return array
     * @throws \App\Exception\ServicioCsvException
     * @throws \DateMalformedStringException
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function buscarReservas(string $texto): array
    {
        if (empty($texto)) {
            return $this->obtenerReservas();
        }

        return array_filter(
          $this->obtenerReservas(),
          fn(Reserva $reserva) => $reserva->getContieneBusqueda($texto)
        );
    }

    /**
     * Función qye genera el json con las reservas
     *
     * @param  array  $reservas
     *
     * @return string
     * @throws \App\Exception\ServicioCsvException
     * @throws \DateMalformedStringException
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    private function generarReporteJson(array $reservas = []): string
    {
        $reservasAExportar = (empty($reservas)) ? $this->obtenerReservas(
        ) : $reservas;

        return $this->jsonDataService->convertirDatosAJson($reservasAExportar);
    }

    /**
     * Función que permite generar la descarga del archivo json creado
     *
     * @param  array  $reservas
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \App\Exception\ServicioCsvException
     * @throws \DateMalformedStringException
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function generarDescargaReporteJson(array $reservas = []): Response
    {
        $json = $this->generarReporteJson($reservas);

        return $this->jsonDataService->generarDescargaJson($json);
    }


}