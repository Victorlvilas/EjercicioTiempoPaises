<?php

namespace App\EventListener;

use App\Exception\ApiBadRequestException;
use App\Exception\ApiConnectionException;
use App\Exception\ApiServerException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ApiExceptionListener
{
    public function __construct(
        private LoggerInterface $logger
    ) {}

    public function onKernelException(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();

        //  ERROR 400 - Petición incorrecta
        if ($e instanceof ApiBadRequestException) {

            $this->logger->warning('Error 400 en API externa', [
                'exception' => $e
            ]);

            $response = new JsonResponse([
                'error' => 'Petición incorrecta'
            ], 400);
        }

        // ERROR 502 - Fallo del servidor externo
        elseif ($e instanceof ApiServerException) {

            $this->logger->error('Error 502 en API externa', [
                'exception' => $e
            ]);

            $response = new JsonResponse([
                'error' => 'Servicio externo fallando'
            ], 502);
        }

        // ERROR 503 - Problema de conexión
        elseif ($e instanceof ApiConnectionException) {

            $this->logger->critical('Error de conexión API externa', [
                'exception' => $e
            ]);

            $response = new JsonResponse([
                'error' => 'No se pudo conectar'
            ], 503);
        }

        // ERROR GENÉRICO
        else {

            $this->logger->error('Error inesperado', [
                'exception' => $e
            ]);

            $response = new JsonResponse([
                'error' => 'Error interno'
            ], 500);
        }

        //  IMPORTANTE: sustituye la respuesta de Symfony
        $event->setResponse($response);
    }
}