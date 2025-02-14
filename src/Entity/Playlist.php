<?php

namespace App\Entity;

use App\Repository\PlaylistRepository;
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
}
