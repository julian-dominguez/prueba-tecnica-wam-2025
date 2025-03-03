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
      $message = "Error al conectar con el servidor que provee el CSV",
      $code = 500,
      Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

}