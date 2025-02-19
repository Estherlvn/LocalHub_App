<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AuditeurController extends AbstractController
{
    #[Route('/auditeur', name: 'app_auditeur')]
    public function index(): Response
    {
        return $this->render('auditeur/index.html.twig', [
            'controller_name' => 'AuditeurController',
        ]);
    }
}
