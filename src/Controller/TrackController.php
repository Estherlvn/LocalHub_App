<?php

namespace App\Controller;

use App\Entity\Track;
use App\Form\TrackFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TrackController extends AbstractController
{
    // Afficher le formulaire d'ajout de morceau
    #[Route('/track/add', name: 'add_track')]
    #[IsGranted('ROLE_ARTISTE')] // Seuls les artistes peuvent ajouter un morceau
    public function addTrack(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser(); // Récupérer l'artiste connecté

        $track = new Track();
        $track->setUser($user); // Associer l’artiste au morceau

        $form = $this->createForm(TrackFormType::class, $track);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($track);
            $entityManager->flush();

            // Message flash de confirmation
            $this->addFlash('success', 'Morceau ajouté avec succès !');

            return $this->redirectToRoute('add_track'); // Redirection vers la liste des morceaux
        }

        return $this->render('track/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
