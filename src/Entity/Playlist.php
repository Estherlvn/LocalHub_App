<?php

namespace App\Entity;

use App\Repository\PlaylistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlaylistRepository::class)]
class Playlist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $playlistName = null;

    #[ORM\Column(nullable: true)]
    private ?bool $visibility = null;

    #[ORM\ManyToOne(inversedBy: 'playlists')]
    private ?User $user = null;

    /**
     * @var Collection<int, track>
     */
    #[ORM\ManyToMany(targetEntity: track::class, inversedBy: 'playlists')]
    private Collection $tracks;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'likedPlaylists')]
    private Collection $likedBy;

    public function __construct()
    {
        $this->tracks = new ArrayCollection();
        $this->likedBy = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlaylistName(): ?string
    {
        return $this->playlistName;
    }

    public function setPlaylistName(string $playlistName): static
    {
        $this->playlistName = $playlistName;

        return $this;
    }

    public function isVisibility(): ?bool
    {
        return $this->visibility;
    }

    public function setVisibility(?bool $visibility): static
    {
        $this->visibility = $visibility;

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

    /**
     * @return Collection<int, track>
     */
    public function getTracks(): Collection
    {
        return $this->tracks;
    }

    public function addTrack(track $track): static
    {
        if (!$this->tracks->contains($track)) {
            $this->tracks->add($track);
        }

        return $this;
    }

    public function removeTrack(track $track): static
    {
        $this->tracks->removeElement($track);

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getLikedBy(): Collection
    {
        return $this->likedBy;
    }

    public function addLikedBy(User $likedBy): static
    {
        if (!$this->likedBy->contains($likedBy)) {
            $this->likedBy->add($likedBy);
        }

        return $this;
    }

    public function removeLikedBy(User $likedBy): static
    {
        $this->likedBy->removeElement($likedBy);

        return $this;
    }
}
