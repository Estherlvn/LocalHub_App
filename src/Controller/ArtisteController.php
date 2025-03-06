<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\TrackRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArtisteController extends AbstractController
{


    #[Route('/artiste/index', name: 'artiste_index')]
    public function index(UserRepository $userRepository, TrackRepository $trackRepository): Response
    {
        // Récupérer tous les artistes (Users ayant le rôle "artiste")
        $artistes = $userRepository->findBy(['role' => 'artiste']);
    
        // Récupérer les derniers morceaux ajoutés (5 par défaut)
        $latestTracks = $trackRepository->findLatestTracks();
    
        // Récupérer les régions contenant des artistes
        $regions = $userRepository->findRegionsWithArtists(); 
    
        return $this->render('artiste/index.html.twig', [
            'artistes' => $artistes,
            'latestTracks' => $latestTracks, // Envoi des morceaux à la vue
            'regions' => $regions, // Envoi des régions à la vue
        ]);
    }
            


}



