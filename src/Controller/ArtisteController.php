<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArtisteController extends AbstractController
{
    #[Route('/artiste/home', name: 'artiste_home')]
    public function index(): Response
    {

        $user = $this->getUser();

        return $this->render('artiste/home.html.twig', [
            'controller_name' => 'ArtisteController',
        ]);
    }
}