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
use App\Service\GeolocationService;
use Symfony\Component\HttpFoundation\JsonResponse;

final class EventController extends AbstractController
{

    // Afficher les évènements à venir (accessible au public)
    #[Route('/event/public', name: 'event_public')]
    public function showPublicEvents(EventRepository $eventRepository): Response
    {
        // Récupérer les événements à venir depuis le repository
        $events = $eventRepository->findUpcomingEvents();

        return $this->render('event/public.html.twig', [
            'events' => $events,
        ]);
    }

    // Afficher le détail d'un event accessible au public
    #[Route('/event/{id}', name: 'event_detail', requirements: ['id' => '\d+'])]
    public function showEventDetail(int $id, EventRepository $eventRepository): Response
    {
        // Récupérer l'événement par son ID
        $event = $eventRepository->find($id);

        // Si l'événement n'existe pas, afficher une erreur 404
        if (!$event) {
            throw $this->createNotFoundException('Événement introuvable.');
        }

        return $this->render('event/detail.html.twig', [
            'event' => $event,
        ]);
    }

    // Méthode pour utiliser le service GeolocationService.php en cas d'appel direct à l'API Nominatim
    #[Route('/event/get-coordinates', name: 'get_event_coordinates', methods: ['POST'])]
    public function getEventCoordinates(Request $request, GeolocationService $geolocationService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $address = $data['address'] ?? null;

        if (!$address) {
            return new JsonResponse(['error' => 'Adresse manquante'], Response::HTTP_BAD_REQUEST);
        }

        $coordinates = $geolocationService->getCoordinates($address);

        if (!$coordinates) {
            return new JsonResponse(['error' => 'Adresse introuvable'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($coordinates);
    }


    // Afficher les événements d'un artiste (sans tri)
    #[Route('/event/list', name: 'event_list')]
    #[IsGranted('ROLE_ARTISTE')]
    public function index(EventRepository $eventRepository): Response
    {
        $user = $this->getUser(); // Récupérer l'utilisateur connecté

        // Récupérer les événements de l'artiste connecté
        $events = $eventRepository->findBy(['user' => $user]);

        return $this->render('event/list.html.twig', [
            'events' => $events,
        ]);
    }

    // Ajouter un événement
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

            return $this->redirectToRoute('event_list'); 
        }

        return $this->render('event/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
