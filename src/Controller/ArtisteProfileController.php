<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileFormType;
use App\Service\FileUploader;
use App\Form\ArtisteProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\ArtisteProfileController;

class ArtisteProfileController extends AbstractController
{
    #[Route('/artiste/profile', name: 'profile_edit')]
    #[IsGranted('ROLE_USER')] // Seuls les utilisateurs connectés peuvent modifier leur profil
    public function editProfile(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ArtisteProfileType::class, $user);
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

            return $this->redirectToRoute('profile_edit');
        }

        return $this->render('artiste/profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
