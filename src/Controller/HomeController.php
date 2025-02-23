<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\TrackRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    // Afficher tous les artistes de la BDD, les derniers titres ajoutés, les artistes triés par régions
    #[Route('/home', name: 'app_home')]
    public function index(UserRepository $userRepository, TrackRepository $trackRepository): Response
    {
        // Récupérer tous les artistes (Users ayant le rôle "artiste")
        $artistes = $userRepository->findBy(['role' => 'artiste']);

        // Récupérer les derniers morceaux ajoutés (5 par défaut)
        $latestTracks = $trackRepository->findLatestTracks();

        // Récupérer les régions contenant des artistes
        $regions = $userRepository->findRegionsWithArtists(); 

        return $this->render('home/index.html.twig', [
            'artistes' => $artistes,
            'latestTracks' => $latestTracks, // Envoi des morceaux à la vue
            'regions' => $regions, // Envoi des régions à la vue
        ]);
    }


    // Afficher les artistes par région
    #[Route('/artistes/{region}', name: 'artistes_par_region')]
    public function artistsByRegion(UserRepository $userRepository, string $region): Response
    {
        // Vérifier que la région est valide en cherchant si elle existe dans la liste des départements
        $departements = [];
        foreach (\App\Service\RegionHelper::getDepartementsRegions() as $departement => $regionName) {
            if ($regionName === $region) {
                $departements[] = $departement;
            }
        }
    
        if (empty($departements)) {
            throw $this->createNotFoundException("La région spécifiée n'existe pas.");
        }
    
        // Récupérer les artistes appartenant aux départements de cette région
        $artistes = $userRepository->findArtistsByRegion($region);
    
        return $this->render('home/region.html.twig', [
            'region' => $region,
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