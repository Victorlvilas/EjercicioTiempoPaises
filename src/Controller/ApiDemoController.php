<?php

namespace App\Controller;

use App\Service\CountriesApiClientService;
use App\Service\CountryWeatherAggregatorService;
use App\Service\LibrosService;
use App\Service\WeatherApiClientService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/api/demo', name: 'app_api_demo_')]
final class ApiDemoController extends AbstractController
{

    public function __construct(private HttpClientInterface $client) {}

    #[Route('/ejemplo1', name: 'ejemplo1')]
    public function ejemplo1(): JsonResponse
    {
        //Consulta API REST externa
        $response = $this->client->request(
            'GET',
            'https://jsonplaceholder.typicode.com/posts?userId=1'
        );

        $statusCode = $response->getStatusCode();
        dump($statusCode);
        // $statusCode = 200
        $contentType = $response->getHeaders()['content-type'][0];
        dump($contentType);
        // $contentType = 'application/json'
        $content = $response->getContent();
        dump($content);

        $content = $response->toArray();
        dump($content);

        return $this->json($response->toArray());
    }


    #[Route('/ejemplo2', name: 'ejemplo2')]
    public function ejemplo2(): JsonResponse
    {
        //Consulta API REST local
        $response = $this->client->request(
            'GET',
            'https://localhost:8001/api/v2/libros'
        );

        $content = $response->toArray();
        return $this->json($content);
    }

    #[Route('/ejemplo3', name: 'ejemplo3')]
    public function ejemplo3(): JsonResponse
    {
        $response = $this->client->request(
            'POST',
            'http://localhost:8001/api/v2/libros',
            [
                'json' =>
                ['titulo' => 'HttpClient', 'descripcion' => 'Libro sobre HttpClient de Symfony']
            ]
        );

        $content = $response->toArray();
        return $this->json($content);
    }

    #[Route('/ejemplo4', name: 'ejemplo4')]
    public function ejemplo4(LibrosService $librosService): JsonResponse
    {
        $librosService->createLibro(['titulo' => 'HC Service', 'descripcion' => 'Libro sobre HttpClient de Symfony y servicio']);
        $libros = $librosService->getLibros();
        return $this->json($libros);
    }

    #[Route('/ejemplo5', name: 'ejemplo5')]
    public function ejemplo5(WeatherApiClientService $weatherService): JsonResponse
    {
        $response = $weatherService->getWeatherByCity('Madrid');
        return $this->json($response);
    }

    #[Route('/ejemplo6', name: 'ejemplo6')]
    public function ejemplo6(CountriesApiClientService $countriesService): JsonResponse
    {
        $response = $countriesService->getAllCountries();
        return $this->json($response);
    }


    #[Route('/countries/top5', name: 'ejemplo7')]
    public function ejemplo7(CountryWeatherAggregatorService $countriesWeatherService): JsonResponse
    {
        $response = $countriesWeatherService->getCountriesWithWeather();
        return $this->json($response);
    }
}
