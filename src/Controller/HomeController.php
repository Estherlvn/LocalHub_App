<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    // Afficher tous les artistes de la BDD
    #[Route('/home', name: 'app_home')]
    public function index(UserRepository $userRepository): Response
    {
        // Récupérer tous les utilisateurs avec le rôle "ARTISTE"
        $artistes = $userRepository->findBy(['role' => 'artiste']);

        // Envoyer les artistes à la vue Twig
        return $this->render('home/index.html.twig', [
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