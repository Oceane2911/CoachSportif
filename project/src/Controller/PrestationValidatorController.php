<?php

namespace App\Controller;

use App\Entity\Prestation;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PrestationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/validation')]
final class PrestationValidatorController extends AbstractController
{
    #[Route('/', name: 'app_validation')]
    public function index(PrestationRepository $prestationRepository): Response
    {
        return $this->render('prestation_validator/index.html.twig', [
            'prestations' => $prestationRepository->findAll(),
        ]);
    }

    #[Route('/validate/{id}', name: 'app_prestation_validate', methods: ['POST'])]
    public function validate(
        Prestation $prestation,
        EntityManagerInterface $entityManager
    ): Response {
        $prestation->setIsValid(true);

        $entityManager->flush();

        return $this->redirectToRoute('app_validation');
    }
}
