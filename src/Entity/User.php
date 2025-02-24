<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'Un compte existe déjà avec cet email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $pseudo = null;

    #[ORM\Column(length: 50, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column]
    private bool $isVerified = false;

    #[ORM\Column(length: 50)]
    private ?string $role = null;



    /**
     * @var Collection<int, Track>
     */
    #[ORM\OneToMany(targetEntity: Track::class, mappedBy: 'user')]
    private Collection $tracks;

    /**
     * @var Collection<int, Playlist>
     */
    #[ORM\OneToMany(targetEntity: Playlist::class, mappedBy: 'user')]
    private Collection $playlists;

    /**
     * @var Collection<int, Playlist>
     */
    #[ORM\ManyToMany(targetEntity: Playlist::class, mappedBy: 'likedBy')]
    private Collection $likedPlaylists;

    #[ORM\Column(length: 255)]
    private ?string $departement = null;

    /**
     * @var Collection<int, Track>
     */
    #[ORM\ManyToMany(targetEntity: Track::class, inversedBy: 'users')]
    private Collection $favoris;


    
    public function __construct()
    {
        $this->tracks = new ArrayCollection();
        $this->playlists = new ArrayCollection();
        $this->likedPlaylists = new ArrayCollection();
        $this->favoris = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Renvoie l'identifiant unique de l'utilisateur.
     * Ici, nous utilisons l'email.
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * Renvoie les rôles de l'utilisateur.
     * Pour l'instant, on renvoie un rôle par défaut.
     */

    public function getRoles(): array
    {
        $role = $this->role ? 'ROLE_' . strtoupper($this->role) : 'ROLE_USER';
        return [$role, 'ROLE_USER'];
    }
    

    /**
     * Méthode permettant d'effacer les données sensibles, si nécessaire.
     */
    public function eraseCredentials(): void
    {
        // Si tu stockes des données sensibles temporaires, efface-les ici.
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Collection<int, Track>
     */
    public function getTracks(): Collection
    {
        return $this->tracks;
    }

    public function addTrack(Track $track): static
    {
        if (!$this->tracks->contains($track)) {
            $this->tracks->add($track);
            $track->setUser($this);
        }

        return $this;
    }

    public function removeTrack(Track $track): static
    {
        if ($this->tracks->removeElement($track)) {
            // set the owning side to null (unless already changed)
            if ($track->getUser() === $this) {
                $track->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Playlist>
     */
    public function getPlaylists(): Collection
    {
        return $this->playlists;
    }

    public function addPlaylist(Playlist $playlist): static
    {
        if (!$this->playlists->contains($playlist)) {
            $this->playlists->add($playlist);
            $playlist->setUser($this);
        }

        return $this;
    }

    public function removePlaylist(Playlist $playlist): static
    {
        if ($this->playlists->removeElement($playlist)) {
            // set the owning side to null (unless already changed)
            if ($playlist->getUser() === $this) {
                $playlist->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Playlist>
     */
    public function getLikedPlaylists(): Collection
    {
        return $this->likedPlaylists;
    }

    public function addLikedPlaylist(Playlist $likedPlaylist): static
    {
        if (!$this->likedPlaylists->contains($likedPlaylist)) {
            $this->likedPlaylists->add($likedPlaylist);
            $likedPlaylist->addLikedBy($this);
        }

        return $this;
    }

    public function removeLikedPlaylist(Playlist $likedPlaylist): static
    {
        if ($this->likedPlaylists->removeElement($likedPlaylist)) {
            $likedPlaylist->removeLikedBy($this);
        }

        return $this;
    }

    public function getDepartement(): ?string
    {
        return $this->departement;
    }

    public function setDepartement(string $departement): static
    {
        $this->departement = $departement;

        return $this;
    }

    /**
     * @return Collection<int, Track>
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    public function addFavori(Track $favori): static
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris->add($favori);
        }

        return $this;
    }

    public function removeFavori(Track $favori): static
    {
        $this->favoris->removeElement($favori);

        return $this;
    }
}
