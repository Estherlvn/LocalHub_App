<?php


namespace App\Controller;

use App\Entity\Track;
use App\Form\TrackFormType;
use App\Service\FileUploader;
use App\Controller\TrackController;
use App\Repository\TrackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class TrackController extends AbstractController
{

    // Afficher les morceaux d'un artiste (sans tri)
        #[Route('/track/collection', name: 'track_collection')]
        #[IsGranted('ROLE_ARTISTE')]
                public function index(TrackRepository $trackRepository): Response
                {
                    $user = $this->getUser(); // Récupérer l'utilisateur connecté

                    // Récupérer les morceaux (tracks) de l'artiste connecté
                    $tracks = $trackRepository->findBy(['user' => $user]);

            
                    return $this->render('track/collection.html.twig', [
                        'tracks' => $tracks,

                    ]);
                }


    // Méthode pour supprimer un Track parmi la liste des pistes d'un artiste connecté
        #[Route('/track/{id}/delete', name: 'remove_track', methods: ['POST', 'GET'])]
        #[IsGranted('ROLE_ARTISTE')]
            public function removeTrack(Track $track, EntityManagerInterface $entityManager): RedirectResponse
            {
                $user = $this->getUser(); // Récupérer l’artiste connecté

                // Vérifier que le morceau appartient bien à l'artiste connecté
                if ($track->getUser() !== $user) {
                    throw $this->createAccessDeniedException("Vous ne pouvez supprimer que vos propres morceaux.");
                }

                // Supprimer le morceau de la base de données
                $entityManager->remove($track);
                $entityManager->flush();

                // Message flash pour confirmer la suppression
                $this->addFlash('success', 'Morceau supprimé avec succès.');

                return $this->redirectToRoute('track_collection'); // Redirection après suppression
            }


    // Lecteur audio pour lire une piste (test)
        #[Route('/track/show/{id}', name: 'track_show')]
            public function show(Track $track): Response
            {
                return $this->render('track/show.html.twig', [
                    'track' => $track,
                ]);
            }
    

    // AJOUTER un morceau / UPLOAD TRACK (dans la session d'un membre artiste connecté)
        #[Route('/track/add', name: 'add_track')]
        #[IsGranted('ROLE_ARTISTE')] // Seuls les artistes peuvent ajouter un morceau
            public function addTrack(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
            {
                $user = $this->getUser(); // Récupérer l'artiste connecté

                $track = new Track();
                $track->setUser($user); // Associer l’artiste au morceau

                $form = $this->createForm(TrackFormType::class, $track);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    /** @var UploadedFile $audioFile */
                    $audioFile = $form->get('audioFile')->getData();

                    // dump($audioFile); // Vérifier si le fichier est bien récupéré
                    // die();

                    if ($audioFile) {
                        // Utiliser le service FileUploader pour gérer l'upload
                        $fileName = $fileUploader->upload($audioFile);
                        $track->setAudioFile($fileName);
                    }

                    $entityManager->persist($track);
                    $entityManager->flush();

                    // Message flash de confirmation
                    $this->addFlash('success', 'Morceau ajouté avec succès !');

                    return $this->redirectToRoute('track_collection');
                }

                return $this->render('track/add.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
}

