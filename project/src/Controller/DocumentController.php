<?php

namespace App\Controller;

use App\Entity\Document;
use App\Entity\Prestation;
use App\Repository\DocumentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\ByteString;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

final class DocumentController extends AbstractController
{
    #[Route('/document/upload/{id}', name: 'app_document_upload', methods: ['POST'])]
    public function upload(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
        Environment $twig
    ): Response {
        $prestation = $entityManager->getRepository(Prestation::class)->find($id);

        if (!$prestation) {
            throw $this->createNotFoundException('Prestation non trouvée.');
        }

        $file = $request->files->get('document');

        if (!$file) {
            $this->addFlash('error', 'Aucun fichier sélectionné.');
            return $this->redirectToRoute('app_prestation_show', ['id' => $id]);
        }

        // Vérifie le type (PDF ou Word)
        $allowedMimes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            $this->addFlash('error', 'Format non autorisé. PDF ou Word uniquement.');
            return $this->redirectToRoute('app_prestation_show', ['id' => $id]);
        }

        // Déplacement du fichier
        $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/documents';
        $fileName = uniqid() . '.' . $file->guessExtension();
        $file->move($uploadDir, $fileName);

        // Génération du code d'accès
        $accessCode = ByteString::fromRandom(10)->toString();

        $document = new Document();
        $document->setUuid(\Symfony\Component\Uid\Uuid::v4());
        $document->setPath('uploads/documents/' . $fileName);
        $document->setAccessCode($accessCode);
        $document->setPrestation($prestation);

        $entityManager->persist($document);
        $entityManager->flush();

        // Envoi du mail avec le code d'accès au client
        $html = $twig->render('email/document_access.html.twig', [
            'prestation' => $prestation,
            'accessCode' => $accessCode,
        ]);

        $email = (new Email())
            ->from('noreply@coachsportif.fr')
            ->to($prestation->getEmail())
            ->subject('📄 Accès à votre document')
            ->html($html);

        $mailer->send($email);

        $this->addFlash('success', 'Document uploadé et email envoyé au client.');

        return $this->redirectToRoute('app_prestation_show', ['id' => $id]);
    }

    #[Route('/document/access', name: 'app_document_access', methods: ['GET', 'POST'])]
    public function access(
        Request $request,
        DocumentRepository $documentRepository
    ): Response {
        $document = null;
        $error = null;

        if ($request->isMethod('POST')) {
            $code = $request->request->get('code');
            $document = $documentRepository->findOneBy(['accessCode' => $code]);

            if (!$document) {
                $error = 'Code invalide. Aucun document trouvé.';
            }
        }

        return $this->render('document/access.html.twig', [
            'document' => $document,
            'error'    => $error,
        ]);
    }
}