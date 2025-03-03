<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventFormType;
use App\Repository\EventRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class EventController extends AbstractController
{
    // ✅ Afficher les événements d'un artiste (sans tri)
    #[Route('/event/list', name: 'event_list')]
    #[IsGranted('ROLE_ARTISTE')]
    public function index(EventRepository $eventRepository): Response
    {
        $user = $this->getUser(); // Récupérer l'utilisateur connecté

        // Récupérer les événements de l'artiste connecté
        $events = $eventRepository->findBy(['user' => $user]);

        return $this->render('event/list.html.twig', [
            'events' => $events, // ✅ Correction de la variable
        ]);
    }

    // ✅ Ajouter un événement
    #[Route('/event/add', name: 'add_event')]
    #[IsGranted('ROLE_USER')] // Seuls les utilisateurs connectés peuvent ajouter un événement
    public function addEvent(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $event = new Event();
        $event->setUser($this->getUser());

        // Création du formulaire
        $form = $this->createForm(EventFormType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $eventPicture */
            $eventPicture = $form->get('eventPicture')->getData();

            if ($eventPicture) {
                try {
                    $fileName = $fileUploader->upload($eventPicture);
                    $event->setEventPicture($fileName);
                } catch (\Exception $e) {
                    $this->addFlash('danger', 'Erreur lors du téléversement de l\'image.');
                    return $this->redirectToRoute('add_event');
                }
            }

            $entityManager->persist($event);
            $entityManager->flush();

            $this->addFlash('success', 'Votre événement a été créé avec succès !');

            return $this->redirectToRoute('event_list'); // ✅ Redirection vers la liste des événements
        }

        return $this->render('event/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
