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

    // Rediriger vers la vue 'détail d'un artiste' (:id)
    #[Route('/artiste/{id}', name: 'artiste_detail')]
    public function showArtist(UserRepository $userRepository, int $id): Response
    {
        // Récupérer l'artiste par son ID
        $artiste = $userRepository->find($id);

        // Vérifier si l'artiste existe
        if (!$artiste) {
            throw $this->createNotFoundException("L'artiste demandé n'existe pas.");
        }

        // Rendre la page détail avec l'artiste
        return $this->render('artiste/detail.html.twig', [
            'artiste' => $artiste,
        ]);
    }
}