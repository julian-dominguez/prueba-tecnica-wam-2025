<?php

namespace App\Entity;

class Reserva
{

    private int $localizador;

    private string $huesped;

    private \DateTimeImmutable $fechaEntrada;

    private \DateTimeImmutable $fechaSalida;

    private string $hotel;

    private float $precio;

    private string $posiblesAcciones;

    /**
     * Permite crear una instancia de Reserva a partir de un array asociativo
     *
     * @param  array  $datos
     *
     * @return \App\Entity\Reserva
     * @throws \DateMalformedStringException
     */
    public static function crearDesdeArray(array $datos): self
    {
        $reserva = new self();
        $reserva->localizador = $datos['localizador'] ?? 0;
        $reserva->huesped = $datos['huesped'] ?? '';
        $reserva->fechaEntrada = isset($datos['fechaEntrada'])
          ? new \DateTimeImmutable($datos['fechaEntrada'])
          : new \DateTimeImmutable();
        $reserva->fechaSalida = isset($datos['fechaSalida'])
          ? new \DateTimeImmutable($datos['fechaEntrada'])
          : new \DateTimeImmutable();
        $reserva->hotel = $datos['hotel'] ?? '';
        $reserva->precio = (float)($datos['precio'] ?? 0);
        $reserva->posiblesAcciones = $datos['posiblesAcciones'] ?? '';

        return $reserva;
    }

    /**
     * Función que permite retornar la reserva como array asociativo.
     *
     * @return array
     */
    public function convertirEnArray(): array
    {
        return [
          'localizador' => $this->localizador,
          'huesped' => $this->huesped,
          'fechaEntrada' => $this->fechaEntrada->format('Y-m-d'),
          'fechaSalida' => $this->fechaSalida->format('Y-m-d'),
          'hotel' => $this->hotel,
          'precio' => $this->precio,
          'posiblesAcciones' => $this->posiblesAcciones,
        ];
    }

    /**
     * Función que retorna si la reserva contiene el parámetro de búsqueda
     *
     * @param  string  $texto
     *
     * @return bool
     */
    public function getContieneBusqueda(string $texto): bool
    {
        $texto = strtolower($texto);
        $campos = [
          $this->localizador,
          $this->huesped,
          $this->fechaEntrada->format('Y-m-d'),
          $this->fechaSalida->format('Y-m-d'),
          $this->hotel,
          $this->precio,
          $this->posiblesAcciones,
        ];

        foreach ($campos as $campo) {
            if (str_contains(strtolower($campo), $texto)) {
                return true;
            }
        }

        return false;
    }

    public function getLocalizador(): int
    {
        return $this->localizador;
    }

    public function getHuesped(): string
    {
        return $this->huesped;
    }

    public function getFechaEntrada(): \DateTimeImmutable
    {
        return $this->fechaEntrada;
    }

    public function getFechaSalida(): \DateTimeImmutable
    {
        return $this->fechaSalida;
    }

    public function getHotel(): string
    {
        return $this->hotel;
    }

    public function getPrecio(): float
    {
        return $this->precio;
    }

    public function getPosiblesAcciones(): string
    {
        return $this->posiblesAcciones;
    }

}