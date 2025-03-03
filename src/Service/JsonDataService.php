<?php

namespace App\Service;

use App\Entity\Reserva;
use Symfony\Component\HttpFoundation\Response;

class JsonDataService
{

    /**
     * @param  array  $reservas
     *
     * @return false|string
     */
    public function convertirDatosAJson(array $reservas): false|string
    {
        $datos = array_map(fn(Reserva $reserva) => $reserva->convertirEnArray(),
          $reservas);
        $datos = array_values($datos);

        return json_encode($datos, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param  string  $contenido
     * @param  string  $nombreArchivo
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function generarDescargaJson(
      string $contenido,
      string $nombreArchivo = 'reservas'
    ): Response {
        $nombreArchivo = $nombreArchivo.'-'.time().'.json';
        $response = new Response($contenido);
        $response->headers->set(
          'Content-Disposition',
          'attachment; filename="'.$nombreArchivo.'"'
        );
        $response->headers->set(
          'Content-Type',
          'application/json'
        );

        return $response;
    }

}