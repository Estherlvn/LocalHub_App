<?php

namespace App\Controller;


use App\Repository\TrackRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ArtisteController extends AbstractController
{


    #[Route('/artiste/home', name: 'artiste_home')]
    #[IsGranted('ROLE_ARTISTE')]
            public function index(TrackRepository $trackRepository): Response
            {
                $user = $this->getUser(); // Récupérer l'utilisateur connecté

                // Récupérer les morceaux (tracks) de l'artiste connecté
                $tracks = $trackRepository->findBy(['user' => $user]);

        
                return $this->render('artiste/home.html.twig', [
                    'tracks' => $tracks,

                ]);
            }


}
