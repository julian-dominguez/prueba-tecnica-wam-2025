<?php

namespace App\Service;

use App\Entity\Reserva;
use App\Exception\ServicioCsvException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Servicio para obtener y procesar datos CSV desde una fuente externa
 */
class CsvService
{

    public function __construct(
      private readonly HttpClientInterface $httpClient,
      private readonly string $endpoint,
      private readonly string $user,
      private readonly string $password,
    ) {}

    /**
     * @return array|string
     * @throws \App\Exception\ServicioCsvException
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function obtenerDatosCsv(): array|string
    {
        try {
            $response = $this->httpClient->request('GET', $this->endpoint, [
              'auth_basic' => [$this->user, $this->password],
            ]);

            if ($response->getStatusCode() !== 200) {
                throw new ServicioCsvException(
                  "Error al conectar con el servidor que provee el CSV",
                  $response->getStatusCode()
                );
            }

            return $response->getContent();
        } catch (\Exception $e) {
            throw new ServicioCsvException(
              "Error al obtener los datos del CSV: ".$e->getMessage()
            );
        }
    }

    /**
     * Función que convierte el contenido del csv en un array de objetos tipo
     * Reserva
     *
     * @param  string  $datos
     *
     * @return array
     * @throws \DateMalformedStringException
     */
    public function procesarDatosCsv(string $datos): array
    {
        $cabeceras = [
          "localizador",
          "huesped",
          "fechaEntrada",
          "fechaSalida",
          "hotel",
          "precio",
          "posiblesAcciones",
        ];
        $lineas = explode("\n", $datos);
        $reservas = [];

        foreach ($lineas as $linea) {
            if (empty($linea)) {
                continue;
            } // ignoramos líneas vacías de haberlas
            $datos_linea = str_getcsv(
              $linea,
              ";"
            ); // Convertimos la linea a un arreglo
            $reserva = array_combine(
              $cabeceras,
              $datos_linea
            ); // Combinamos datos

            $reservas[] = Reserva::crearDesdeArray($reserva);
        }

        return $reservas;
    }


}