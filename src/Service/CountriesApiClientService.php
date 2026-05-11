<?php

namespace App\Service;

use App\Exception\ApiBadRequestException;
use App\Exception\ApiConnectionException;
use App\Exception\ApiServerException;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CountriesApiClientService
{
    public function __construct(
        private HttpClientInterface $client,
        private string $countriesApiUrl
    ) {}

    public function getAllCountries(): array
    {
        try {

            $response = $this->client->request(
                'GET',
                $this->countriesApiUrl . '/all',
                [
                    'query' => [
                        'fields' => 'name,capital,population'
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
