<?php

namespace App\Repository;

use App\Service\ReservaService;
use Symfony\Component\HttpFoundation\Response;

/**
 * Repositorio para acceder a los datos de reservas
 * NOTA: Aunque no se maneje conexión a la base de datos se maneja este
 * repositorio para las consultas de datos con base en mantener la integridad
 * de la arquitectura
 */
class ReservaRepository
{

    public function __construct(
      private readonly ReservaService $reservaService
    ) {}

    /**
     * Función que retorna todas las reservas
     *
     * @return array
     * @throws \App\Exception\ServicioCsvException
     * @throws \DateMalformedStringException
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function obtenerTodasLasReservas(): array
    {
        return $this->reservaService->obtenerReservas();
    }

    /**
     * Función que retorna las reservas que coinciden con los parámetros de
     * búsqueda
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
    public function buscarReserva(string $texto): array
    {
        return $this->reservaService->buscarReservas($texto);
    }

    /**
     * Función que genera la descarga del archivo json con las reservas.
     *
     * @param  array  $reservas
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \App\Exception\ServicioCsvException
     * @throws \DateMalformedStringException
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function generarReporteJson(array $reservas = []): Response
    {
        return $this->reservaService->generarDescargaReporteJson($reservas);
    }

}