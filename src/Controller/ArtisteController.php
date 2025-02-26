<?php

namespace App\Controller;


use App\Entity\Track;
use App\Repository\TrackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ArtisteController extends AbstractController
{


    #[Route('/artiste/home', name: 'artiste_home')]
    #[IsGranted('ROLE_ARTISTE')]
            public function index(TrackRepository $trackRepository): Response
            {
                $user = $this->getUser(); // Récupérer l'utilisateur connecté

                // Récupérer les morceaux (tracks) de l'artiste connecté
                $tracks = $trackRepository->findBy(['user' => $user]);

        
                return $this->render('artiste/home.html.twig', [
                    'tracks' => $tracks,

                ]);
            }

    
// Méthode pour supprimer un Track parmi la liste des pistes d'un artiste connecté
    #[Route('/track/{id}/delete', name: 'remove_track', methods: ['POST', 'GET'])]
    #[IsGranted('ROLE_ARTISTE')]
        public function removeTrack(Track $track, EntityManagerInterface $entityManager): RedirectResponse
        {
            $user = $this->getUser(); // Récupérer l’artiste connecté

            // Vérifier que le morceau appartient bien à l'artiste connecté
            if ($track->getUser() !== $user) {
                throw $this->createAccessDeniedException("Vous ne pouvez supprimer que vos propres morceaux.");
            }

            // Supprimer le morceau de la base de données
            $entityManager->remove($track);
            $entityManager->flush();

            // Message flash pour confirmer la suppression
            $this->addFlash('success', 'Morceau supprimé avec succès.');

            return $this->redirectToRoute('artiste_home'); // Redirection après suppression
        }

}
