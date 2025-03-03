<?php

namespace App\Controller;

use App\Exception\ServicioCsvException;
use App\Repository\ReservaRepository;
use App\Service\ReservaService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ReservaController extends AbstractController
{

    public function __construct(
      private readonly ReservaRepository $reservaRepository,
      private readonly ReservaService $reservaService
    ) {}

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \DateMalformedStringException
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    #[Route('/', name: 'app_reservas_index', methods: ['GET'])]
    public function index(): Response
    {
        try {
            $reservas = $this->reservaRepository->obtenerTodasLasReservas();

            return $this->render('reserva/index.html.twig', [
              'reservas' => $reservas,
              'busqueda' => '',
              'error' => null,
            ]);
        } catch (ServicioCsvException $e) {
            return $this->render('reserva/index.html.twig', [
              'reservas' => [],
              'busqueda' => '',
              'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \DateMalformedStringException
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    #[Route('/buscar', name: 'app_reservas_buscar', methods: ['GET'])]
    public function buscar(Request $request): Response
    {
        $textoBusqueda = $request->query->get('q', '');

        try {
            $reservas = $this->reservaService->buscarReservas($textoBusqueda);

            return $this->render('reserva/index.html.twig', [
              'reservas' => $reservas,
              'busqueda' => $textoBusqueda,
              'error' => null,
            ]);
        } catch (ServicioCsvException $e) {
            return $this->render('reserva/index.html.twig', [
              'reservas' => [],
              'busqueda' => $textoBusqueda,
              'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \DateMalformedStringException
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    #[Route('/descargar', name: 'app_reservas_descargar', methods: ['GET'])]
    public function descargarJson(Request $request): Response
    {
        $textoBusqueda = $request->query->get('q', '');
        try {
            // Si hay parámetro de búsqueda, descargamos solo las reservas filtradas
            $reservas = $textoBusqueda
              ? $this->reservaRepository->buscarReserva($textoBusqueda)
              : [];

            return $this->reservaService->generarDescargaReporteJson($reservas);
        } catch (ServicioCsvException $e) {
            return $this->render('reserva/index.html.twig', [
              'reservas' => [],
              'busqueda' => $textoBusqueda,
              'error' => $e->getMessage(),
            ]);
        }
    }

}
