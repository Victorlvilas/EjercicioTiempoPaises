<?php

namespace App\Command;

use App\Entity\Country;
use App\Repository\CountryRepository;
use App\Service\CountriesApiClientService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:ImportCountries',
    description: 'Consume API de países y los importa a la base de datos',
)]
class ImportCountriesCommand extends Command
{
    public function __construct(
        private CountriesApiClientService $countriesApiClient,
        private EntityManagerInterface $em,
        private CountryRepository $countryRepository,
        private LoggerInterface $logger
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Importación de países');

        $countries = $this->countriesApiClient->getAllCountries();

        $imported = 0;
        $skipped = 0;

        foreach ($countries as $data) {

            $code = trim($data['cca2']);

            //  evitar duplicados por nombre
            $exists = $this->countryRepository->findOneBy([
                'code' => $code
            ]);

            if ($exists) {
                $skipped++;
                $this->logger->info("Saltado país con código {$code} (ya existe)");

                continue;
            }

            $country = new Country();
            $country->setCode($code);
            $country->setName($data['name']['common']);
            $country->setCapital($data['capital'][0] ?? 'N/A');
            $country->setPopulation($data['population']);
            $country->setCountryLat($data['latlng'][0]);
            $country->setCountryLng($data['latlng'][1]);

            $this->em->persist($country);
            $imported++;
            $this->logger->info("Importado país: {$code}");
        }

        $this->em->flush();

        $io->success("Importación completada. Importados: $imported | Saltados: $skipped");

        return Command::SUCCESS;
    }
}
