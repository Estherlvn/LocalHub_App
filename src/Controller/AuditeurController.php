<?php

namespace App\Controller;

use App\Entity\Track;
use App\Repository\TrackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AuditeurController extends AbstractController
{

    
    // AFFICHER les titres de la BDD et les titres FAVORIS de l'auditeur connecté
    #[Route('/auditeur/profile', name: 'auditeur_profile')]
    #[IsGranted('ROLE_AUDITEUR')]
        public function profile(TrackRepository $trackRepository): Response
        {
            $user = $this->getUser(); // Récupérer l'utilisateur connecté

            // Récupérer tous les morceaux de la BDD
            $tracks = $trackRepository->findAll();
            
            // Récupérer les morceaux favoris de l'utilisateur connecté
            $favoriteTracks = $user->getFavoris();
            
            return $this->render('auditeur/profile.html.twig', [
                'tracks' => $tracks,
                'favoriteTracks' => $favoriteTracks,
            ]);
        }
    

    // AFFICHER les titres enregistrés en favoris par un Auditeur
    #[Route('/auditeur/favoris', name: 'auditeur_favoris')]
    #[IsGranted('ROLE_AUDITEUR')]
        public function favoris(TrackRepository $trackRepository): Response
        {
            $user = $this->getUser(); // Récupérer l'utilisateur connecté
            
            // Récupérer tous les morceaux de la BDD
            $tracks = $trackRepository->findAll();
            
            // Récupérer les morceaux favoris de l'utilisateur connecté
            $favoriteTracks = $user->getFavoris();
            
            return $this->render('auditeur/like.html.twig', [
                'tracks' => $tracks,
                'favoriteTracks' => $favoriteTracks,
            ]);
        }
        
    

    // AJOUTER et RETIRER un morceau des FAVORIS
    #[Route('/track/like/{id}', name: 'like_track')]
    #[IsGranted('ROLE_AUDITEUR')]
        public function likeTrack(Track $track, EntityManagerInterface $entityManager): RedirectResponse
        {
            $user = $this->getUser();
            
            if (!$user) {
                $this->addFlash('error', 'Vous devez être connecté pour liker un morceau.');
                return $this->redirectToRoute('app_login');
            }
            
            // Vérifier si l'utilisateur a déjà liké ce morceau
            if ($user->getFavoris()->contains($track)) {
                $user->removeFavori($track);
            } else {
                $user->addFavori($track);
            }
            
            $entityManager->persist($user);
            $entityManager->flush();
            
            // Rediriger vers la page de profil de l'auditeur après l'action
            return $this->redirectToRoute('auditeur_favoris');
        }

}
