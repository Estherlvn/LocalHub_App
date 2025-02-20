<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\TrackRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    // Afficher tous les artistes de la BDD et les derniers titres ajoutés
    #[Route('/home', name: 'app_home')]
    public function index(UserRepository $userRepository, TrackRepository $trackRepository): Response
    {
        // Récupérer tous les artistes (Users ayant le rôle "artiste")
        $artistes = $userRepository->findBy(['role' => 'artiste']);
    
        // Récupérer les derniers morceaux ajoutés (5 par défaut)
        $latestTracks = $trackRepository->findLatestTracks();
    
        return $this->render('home/index.html.twig', [
            'artistes' => $artistes,
            'latestTracks' => $latestTracks, // 🔹 Envoi des morceaux à la vue
        ]);
    }
    

    // Afficher les détails d'un artiste (User avec ID sélectionné)
    #[Route('/artiste/{id}', name: 'artiste_detail')]
    public function showArtist(UserRepository $userRepository, int $id): Response
    {
        // Récupérer l'utilisateur correspondant à l'ID
        $artiste = $userRepository->find($id);

        // Vérifier si l'utilisateur existe et est bien un artiste
        if (!$artiste || $artiste->getRole() !== 'artiste') {
            throw $this->createNotFoundException("L'utilisateur demandé n'est pas un artiste.");
        }

        // Récupérer les morceaux (tracks) de cet artiste
        $tracks = $artiste->getTracks();

        return $this->render('artiste/detail.html.twig', [
            'artiste' => $artiste,
            'tracks' => $tracks, // Passer les morceaux à la vue
            
        ]);
    }
}