<?php

namespace App\Controller;

use App\Entity\Track;
use App\Repository\TrackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AuditeurController extends AbstractController
{
    // Afficher les morceaux de la bdd
        #[Route('/auditeur/home', name: 'auditeur_home')]
            public function index(TrackRepository $trackRepository): Response
            {
                // Récupérer tous les morceaux de la BDD
                $tracks = $trackRepository->findAll();

                return $this->render('auditeur/home.html.twig', [
                    'tracks' => $tracks,
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
        
                // Vérifie si l'utilisateur a déjà liké ce morceau
                if ($user->getFavoris()->contains($track)) {
                    $user->removeFavori($track);
                } else {
                    $user->addFavori($track);
                }
        
                $entityManager->persist($user);
                $entityManager->flush();
        
                return $this->redirectToRoute('auditeur_home'); // Redirection après l'action
            }
        }
    


