<?php

namespace App\Controller;

use App\Entity\Prestation;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PrestationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

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
        int $id,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
        Environment $twig
    ): Response {
        $prestation = $entityManager->getRepository(Prestation::class)->find($id);

        if (!$prestation) {
            throw $this->createNotFoundException('Prestation non trouvée.');
        }

        $prestation->setIsValid(true);
        $entityManager->flush();

        // Envoi du mail de confirmation de rendez-vous
        $html = $twig->render('email/prestation_confirmed.html.twig', [
            'prestation' => $prestation,
        ]);

        $email = (new Email())
            ->from('noreply@coachsportif.fr')
            ->to($prestation->getEmail())
            ->subject('✓ Votre rendez-vous est confirmé')
            ->html($html);

        $mailer->send($email);

        return $this->redirectToRoute('app_validation');
    }
}