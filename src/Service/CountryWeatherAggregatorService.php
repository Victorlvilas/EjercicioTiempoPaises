<?php

namespace App\Service;

use App\Exception\ApiBadRequestException;
use App\Exception\ApiConnectionException;
use App\Exception\ApiServerException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class CountryWeatherAggregatorService
{
    public function __construct(
        private CountriesApiClientService $countriesApi,
        private  WeatherApiClientService $weatherApi
    ) {}

    public function getCountriesWithWeather(): array
    {
        try {

            $paises = $this->countriesApi->getAllCountries();
            $primeros5 = array_slice($paises, 0, 5);

            $arrayPaises = [];

            foreach ($primeros5 as $pais) {
                $nombrePais = $pais['name']['common'];
                $capital = $pais['capital'][0];
                $poblacion = $pais['population'];
                $tiempoCapital =   $this->weatherApi->getWeatherByCity($capital);
                $temperatura = $tiempoCapital['main']['temp'];

                $arrayPaises[] = ['country' => $nombrePais, 'capital' => $capital, 'population' => $poblacion, 'temperature' => $temperatura];
            }
            return $arrayPaises;
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
