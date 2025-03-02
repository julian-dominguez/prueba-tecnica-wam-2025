<?php

namespace App\Exception;

use Throwable;

/**
 * Excepción personalizada para el manejo de los errores de consultar datos de
 * CSV externo
 */
class ServicioCsvException extends \Exception
{

    public function __construct(
      $message = "Error al conectar con el servicio CSV",
      $code = 0,
      Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

}