<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $eventTitle = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $eventDate = null;

    #[ORM\Column(length: 50)]
    private ?string $eventLocation = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $eventDescription = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $eventPicture = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null; // Propriétaire de l'événement (ex: artiste)

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'savedEvents')]
    private Collection $savedByUsers; // Liste des utilisateurs qui ont enregistré cet événement


    public function __construct()
    {
        $this->savedByUsers = new ArrayCollection();
    }


    #[ORM\Column]
    private ?float $latitude = null;

    #[ORM\Column]
    private ?float $longitude = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEventTitle(): ?string
    {
        return $this->eventTitle;
    }

    public function setEventTitle(string $eventTitle): static
    {
        $this->eventTitle = $eventTitle;

        return $this;
    }

    public function getEventDate(): ?\DateTimeInterface
    {
        return $this->eventDate;
    }

    public function setEventDate(\DateTimeInterface $eventDate): static
    {
        $this->eventDate = $eventDate;

        return $this;
    }

    public function getEventLocation(): ?string
    {
        return $this->eventLocation;
    }

    public function setEventLocation(string $eventLocation): static
    {
        $this->eventLocation = $eventLocation;

        return $this;
    }

    public function getEventDescription(): ?string
    {
        return $this->eventDescription;
    }

    public function setEventDescription(string $eventDescription): static
    {
        $this->eventDescription = $eventDescription;

        return $this;
    }

    public function getEventPicture(): ?string
    {
        return $this->eventPicture;
    }

    public function setEventPicture(?string $eventPicture): static
    {
        $this->eventPicture = $eventPicture;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }


     // Récupérer les utilisateurs qui ont enregistré l'événement
     public function getSavedByUsers(): Collection
     {
         return $this->savedByUsers;
     }
 
     // Ajouter un utilisateur qui enregistre cet événement
     public function addSavedByUser(User $user): self
     {
         if (!$this->savedByUsers->contains($user)) {
             $this->savedByUsers->add($user);
             $user->saveEvent($this); // Assurer la relation bidirectionnelle
         }
         return $this;
     }
 
     // Supprimer un utilisateur de la liste des favoris
     public function removeSavedByUser(User $user): self
     {
         if ($this->savedByUsers->contains($user)) {
             $this->savedByUsers->removeElement($user);
             $user->removeEvent($this); // Assurer la relation bidirectionnelle
         }
         return $this;
     }
}
