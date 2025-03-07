<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AuditeurProfileType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuditeurProfileController extends AbstractController
{
    #[Route('/auditeur/profile', name: 'auditeur_profile')]
    #[IsGranted('ROLE_USER')] // Seuls les utilisateurs connectés peuvent accéder à cette page
    public function profile(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Créer et traiter le formulaire
        $form = $this->createForm(AuditeurProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $profilePicture */
            $profilePicture = $form->get('profilePicture')->getData();

            if ($profilePicture) {
                $fileName = $fileUploader->upload($profilePicture);
                $user->setProfilePicture($fileName);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre profil a été mis à jour !');

            return $this->redirectToRoute('auditeur_profile');
        }

        // Passer le formulaire à la vue
        return $this->render('auditeur/profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
