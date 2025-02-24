<?php

namespace App\Controller;

use App\Repository\TrackRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AuditeurController extends AbstractController
{
    #[Route('/auditeur/home', name: 'auditeur_home')]
    public function index(TrackRepository $trackRepository): Response
    {
        // Récupérer tous les morceaux de la BDD
        $tracks = $trackRepository->findAll();

        return $this->render('auditeur/home.html.twig', [
            'tracks' => $tracks,
        ]);
    }
}
