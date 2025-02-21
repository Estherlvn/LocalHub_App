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

        // Récupérer les artistes groupés par région
        $regions = $userRepository->findArtistsGroupedByRegion(); // Voir méthode dans UserRepository
    

        return $this->render('home/index.html.twig', [
            'artistes' => $artistes,
            'latestTracks' => $latestTracks, // Envoi des morceaux à la vue
            'regions' => $regions, // Envoi des régions à la vue
        ]);
    }
    

    // Afficher les artistes par région
    #[Route('/artistes/{departement}', name: 'artistes_par_region')]
    public function artistsByRegion(UserRepository $userRepository, string $departement): Response
    {
        // Vérifier que le département est valide (01-95)
        if (!preg_match('/^(0[1-9]|[1-8][0-9]|9[0-5])$/', $departement)) {
            throw $this->createNotFoundException("Le département spécifié n'est pas valide.");
        }

        // Récupérer les artistes de cette région
        $artistes = $userRepository->findArtistsByRegion($departement);

        return $this->render('home/region.html.twig', [
            'departement' => $departement,
            'artistes' => $artistes,
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