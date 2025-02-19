<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ArtisteController extends AbstractController
{
    #[Route('/artiste', name: 'app_artiste')]
    public function index(): Response
    {
        return $this->render('artiste/index.html.twig', [
            'controller_name' => 'ArtisteController',
        ]);
    }
}
