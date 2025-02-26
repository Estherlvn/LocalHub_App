<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ArtisteController extends AbstractController
{


//     #[Route('/auditeur/home', name: 'auditeur_home')]
//     #[IsGranted('ROLE_ARTISTE')]
//             public function index(TrackRepository $trackRepository): Response
//             {
//                 $user = $this->getUser(); // Récupérer l'utilisateur connecté

//                 // Récupérer tous les morceaux de la BDD
//                 $tracks = $trackRepository->findAll();
        
//                 // Récupérer les morceaux favoris de l'utilisateur connecté
//                 $favoriteTracks = $user->getFavoris();
        
//                 return $this->render('auditeur/home.html.twig', [
//                     'tracks' => $tracks,
//                     'favoriteTracks' => $favoriteTracks,
//                 ]);
//             }


}
