<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MotifController extends AbstractController
{
    #[Route('/motif', name: 'app_motif')]
    public function index(): Response
    {
        return $this->render('motif/index.html.twig', [
            'controller_name' => 'MotifController',
        ]);
    }
}
