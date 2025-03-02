<?php

namespace App\Service;

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

}