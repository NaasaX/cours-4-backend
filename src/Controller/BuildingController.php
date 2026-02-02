<?php

namespace App\Controller;

use App\Repository\BuildingRepository;
use App\Repository\PersonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BuildingController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    #[Route('/buildings', name: 'app_buildings')]
    public function index(BuildingRepository $buildingRepository): Response
    {
        $buildings = $buildingRepository->findAll();

        return $this->render('building/index.html.twig', [
            'buildings' => $buildings,
        ]);
    }

    #[Route('/building/{id}', name: 'app_building_show')]
    public function show(int $id, BuildingRepository $buildingRepository): Response
    {
        $building = $buildingRepository->find($id);

        if (!$building) {
            throw $this->createNotFoundException('Building not found');
        }

        return $this->render('building/show.html.twig', [
            'building' => $building,
        ]);
    }

    #[Route('/people', name: 'app_people')]
    public function people(PersonRepository $personRepository): Response
    {
        $people = $personRepository->findAll();

        return $this->render('building/people.html.twig', [
            'people' => $people,
        ]);
    }
}
