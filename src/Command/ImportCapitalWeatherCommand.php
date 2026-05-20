<?php

namespace App\Command;

use App\Entity\CapitalWeatherMeasurement;
use App\Repository\CountryRepository;
use App\Service\WeatherApiClientService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:ImportCapitalWeather',
    description: 'Imports weather measurements for country capitals',
)]
class ImportCapitalWeatherCommand extends Command
{
    public function __construct(
        private CountryRepository $countryRepository,
        private WeatherApiClientService $weatherClient,
        private EntityManagerInterface $em,
        private LoggerInterface $logger
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $countries = $this->countryRepository->findAll();

        if (count($countries) === 0) {
            $io->warning('No countries found');
            $this->logger->warning('No countries found, skipping weather import');

            return Command::FAILURE;
        }

        foreach ($countries as $country) {

            if (!$country->getCapital()) {
                $io->warning('Country without capital: ' . $country->getName());
                $this->logger->warning("Country without capital: {$country->getName()}");
                continue;
            }

            try {

                $weatherData = $this->weatherClient
                    ->getWeatherByCity($country->getCapital());

                $measurement = new CapitalWeatherMeasurement();

                $measurement->setTemperature(
                    $weatherData['main']['temp']

                );

                $measurement->setWeatherLat(
                    $weatherData['coord']['lat']
                );

                $measurement->setWeatherLng(
                    $weatherData['coord']['lon']
                );

                $measurement->setMeasuredAt(
                    new \DateTimeImmutable()
                );

                $measurement->setCountry($country);

                $this->em->persist($measurement);

                $io->text(
                    sprintf(
                        'Imported weather for %s',
                        $country->getName()
                    )
                );
            } catch (\Throwable $e) {

                $io->error(
                    sprintf(
                        'Error importing %s: %s',
                        $country->getName(),
                        $e->getMessage()
                    )
                );
                $this->logger->error(
                    sprintf(
                        'Error importing weather for %s: %s',
                        $country->getName(),
                        $e->getTraceAsString()
                    )
                );
            }
        }

        $this->em->flush();

        $io->success('Weather measurements imported');

        return Command::SUCCESS;
    }
}
