<?php

namespace App\Command;

use App\Entity\Building;
use App\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:populate-database',
    description: 'Populates the database with fake buildings and people data',
)]
class PopulateDatabaseCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('buildings', 'b', InputOption::VALUE_OPTIONAL, 'Number of buildings to create', 5)
            ->addOption('people-per-building', 'p', InputOption::VALUE_OPTIONAL, 'Number of people per building', 10)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $faker = Factory::create();

        $buildingsCount = (int) $input->getOption('buildings');
        $peoplePerBuilding = (int) $input->getOption('people-per-building');

        $io->title('Populating database with fake data');

        // Create buildings
        $buildings = [];
        $io->section('Creating buildings...');
        for ($i = 0; $i < $buildingsCount; $i++) {
            $building = new Building();
            $building->setName($faker->company() . ' Building');
            $building->setAddress($faker->streetAddress() . ', ' . $faker->city() . ' ' . $faker->postcode());
            $building->setFloors($faker->numberBetween(1, 30));

            $this->entityManager->persist($building);
            $buildings[] = $building;

            $io->writeln(sprintf('  - Created: %s', $building->getName()));
        }

        $this->entityManager->flush();
        $io->success(sprintf('Created %d buildings', $buildingsCount));

        // Create people
        $io->section('Creating people...');
        $totalPeople = 0;
        foreach ($buildings as $building) {
            for ($i = 0; $i < $peoplePerBuilding; $i++) {
                $person = new Person();
                $person->setFirstName($faker->firstName());
                $person->setLastName($faker->lastName());
                $person->setEmail($faker->email());
                $person->setPhone($faker->optional(0.8)->phoneNumber());
                $person->setBuilding($building);

                $this->entityManager->persist($person);
                $totalPeople++;
            }
        }

        $this->entityManager->flush();
        $io->success(sprintf('Created %d people in %d buildings', $totalPeople, $buildingsCount));

        $io->success('Database populated successfully!');

        return Command::SUCCESS;
    }
}
