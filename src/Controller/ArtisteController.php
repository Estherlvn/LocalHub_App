<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArtisteController extends AbstractController
{


    //Récupérer le membre connecté (getUser)
    #[Route('/artiste/home', name: 'artiste_home')]
        public function index(): Response
        {

            $user = $this->getUser();

            return $this->render('artiste/home.html.twig', [
                'controller_name' => 'ArtisteController',
            ]);
        }


    // Permettre à un membre Artiste d'uploader une Image (photo de profil)
    #[Route('/artiste/profile', name: 'artiste_profile')]
    #[IsGranted('ROLE_ARTISTE')]
        public function profile(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
        {
            /** @var User $user */
            $user = $this->getUser();
            $form = $this->createForm(ArtisteProfileType::class, $user);
            $form->handleRequest($request);
        
            if ($form->isSubmitted() && $form->isValid()) {
                $profilePictureFile = $form->get('profilePicture')->getData();
        
                if ($profilePictureFile) {
                    // Utilisez le service FileUploader pour gérer l'upload
                    $fileName = $fileUploader->upload($profilePictureFile);
                    $user->setProfilePicture($fileName);
                }
        
                $entityManager->persist($user);
                $entityManager->flush();
        
                $this->addFlash('success', 'Votre profil a été mis à jour avec succès !');
        
                return $this->redirectToRoute('artiste_profile');
            }
        
            return $this->render('artist/profile.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    
}