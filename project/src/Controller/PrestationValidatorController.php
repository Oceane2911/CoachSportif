<?php

namespace App\Controller;

use App\Repository\PrestationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/validation')]
final class PrestationValidatorController extends AbstractController
{
    #[Route(name: 'app_validation')]
    public function index(PrestationRepository $prestationRepository): Response
    {
        return $this->render('prestation_validator/index.html.twig', [
            'prestations' => $prestationRepository->findAll()
        ]);
    }
}
