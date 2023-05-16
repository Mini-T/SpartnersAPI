<?php

namespace App\Controller;

use App\Repository\SportsHallRepository;
use Doctrine\ORM\EntityManagerInterface;
use \Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class SportsHallController extends AbstractController {
    private $repository;

    public function __construct(SportsHallRepository $repository)
    {
        $this->repository = $repository;
    }


    #[Route('/api/choices', methods: ['GET'])]
    public function getSalleNameAndId() : JsonResponse {
        $salles = $this->repository->findAll();
        $dtos = array_map(function($salle) {
            return array("nom" => $salle->getName(), "id" =>$salle->getId());
        }, $salles);

        return $this->json($dtos);
    }
}