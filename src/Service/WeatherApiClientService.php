<?php

namespace App\Service;

use App\Exception\ApiBadRequestException;
use App\Exception\ApiConnectionException;
use App\Exception\ApiServerException;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherApiClientService
{
    public function __construct(
        private HttpClientInterface $client,
        private string $weatherApiUrl,
        private string $weatherApiKey
    ) {}

    public function getWeatherByCity(string $city): array
    {
        try {

            $response = $this->client->request(
                'GET',
                $this->weatherApiUrl . '/weather',
                [
                    'query' => [
                        'q' => $city,
                        'appid' => $this->weatherApiKey,
                        'units' => 'metric'
                    ]
                ]
            );

            return $response->toArray();
        } catch (ClientExceptionInterface $e) {

            throw new ApiBadRequestException(
                'Error consultando OpenWeather',
                0,
                $e
            );
        } catch (ServerExceptionInterface $e) {

            throw new ApiServerException(
                'OpenWeather no disponible',
                0,
                $e
            );
        } catch (TransportExceptionInterface $e) {

            throw new ApiConnectionException(
                'Error de conexión con OpenWeather',
                0,
                $e
            );
        }
    }
}
