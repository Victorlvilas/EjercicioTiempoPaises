<?php

namespace App\Service;

use App\Exception\ApiBadRequestException;
use App\Exception\ApiConnectionException;
use App\Exception\ApiServerException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class LibrosService
{

    const API_URL = 'https://localhost:8001/api/v2/libros';

    public function __construct(private HttpClientInterface $client) {}


    public function getLibros(): array
    {
        $response = $this->client->request(
            'GET',
            self::API_URL
        );

        $content = $response->toArray();
        return $content;
    }

    public function createLibro(array $data): array
    {
        try {
            $response = $this->client->request(
                'POST',
                self::API_URL,
                [
                    'json' => $data
                ]
            );

            $content = $response->toArray();
            return $content;
            
        } catch (ClientExceptionInterface $e) {
            //getContent(false) para obtener el contenido lanzar una excepción si el código de estado no es 2xx
            throw new ApiBadRequestException('Error en petición externa: ' . $e->getResponse()->getContent(false), 0, $e);
        } catch (ServerExceptionInterface $e) {

            throw new ApiServerException('Error en servicio externo: ' . $e->getResponse()->getContent(false), 0, $e);
        } catch (TransportExceptionInterface $e) {

            throw new ApiConnectionException('Error de conexión: ' . $e->getMessage(), 0, $e);
        }
    }
}
