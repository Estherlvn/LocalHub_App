<?php

namespace App\Controller;

use App\Entity\Track;
use App\Repository\EventRepository;
use App\Repository\TrackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
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


    // AJOUTER et RETIRER un event des events enregistrés
    #[Route('/event/save/{id}', name: 'save_event')]
    #[IsGranted('ROLE_AUDITEUR')]
        public function saveEvent(Event $event, EntityManagerInterface $entityManager): RedirectResponse
        {
            $user = $this->getUser();
            
            // Vérifier si l'événement est déjà enregistré
            if ($user->getSavedEvents()->contains($event)) {
                $user->removeSavedEvent($event);
                $message = 'Événement retiré.';
            } else {
                $user->saveEvent($event);
                $message = 'Événement enregistré !';
            }

            // Sauvegarder les modifications
            $entityManager->flush();

            // Ajouter un message flash pour l'utilisateur
            $this->addFlash('success', $message);

            // Redirection vers la liste des événements favoris
            return $this->redirectToRoute('event_auditeur');
        }

    // AFFICHER les events + events enregistrés de l'auditeur
    #[Route('/event/auditeur', name: 'event_auditeur')]
    #[IsGranted('ROLE_AUDITEUR')]
        public function showEvent(EventRepository $eventRepository): Response
        {
            $user = $this->getUser(); // Récupérer l'utilisateur connecté

            // Récupérer tous les events de la BDD
            $events = $eventRepository->findAll();
            
            // Récupérer les events favoris de l'utilisateur (évite le retour null)
            $savedEvents = $user->getSavedEvents();

            return $this->render('event/auditeur.html.twig', [
                'events' => $events,
                'savedEvents' => $savedEvents,
            ]);
        }

        
}
