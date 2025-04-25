<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PageController extends AbstractController
{
    #[Route('/conditions-utilisation', name: 'cgu')]
    public function cgu(): Response
    {
        return $this->render('pages/cgu.html.twig');
    }
}
