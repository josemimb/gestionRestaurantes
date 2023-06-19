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
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public function __toString(): string
    {
       // return $this->getNombre();
       return $this->getNombre();
    }
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 40, nullable: false)]
    private ?string $nombre = null;

    #[ORM\Column(nullable: true)]
    private ?int $contacto = null;

    #[ORM\Column(nullable: true)]
    private ?int $puntos = null;

    #[ORM\OneToMany(mappedBy: 'usuario', targetEntity: Reserva::class)]
    private Collection $reservas;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Opinion::class)]
    private Collection $opinions;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Restaurante::class)]
    private Collection $restaurantes;

    public function __construct()
    {
        $this->reservas = new ArrayCollection();
        $this->opinions = new ArrayCollection();
        $this->restaurantes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getContacto(): ?int
    {
        return $this->contacto;
    }

    public function setContacto(?int $contacto): self
    {
        $this->contacto = $contacto;

        return $this;
    }

    public function getPuntos(): ?int
    {
        return $this->puntos;
    }

    public function setPuntos(?int $puntos): self
    {
        $this->puntos = $puntos;

        return $this;
    }

    /**
     * @return Collection<int, Reserva>
     */
    public function getReservas(): Collection
    {
        return $this->reservas;
    }

    public function addReserva(Reserva $reserva): self
    {
        if (!$this->reservas->contains($reserva)) {
            $this->reservas->add($reserva);
            $reserva->setUsuario($this);
        }

        return $this;
    }

    public function removeReserva(Reserva $reserva): self
    {
        if ($this->reservas->removeElement($reserva)) {
            // set the owning side to null (unless already changed)
            if ($reserva->getUsuario() === $this) {
                $reserva->setUsuario(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Opinion>
     */
    public function getOpinions(): Collection
    {
        return $this->opinions;
    }

    public function addOpinion(Opinion $opinion): self
    {
        if (!$this->opinions->contains($opinion)) {
            $this->opinions->add($opinion);
            $opinion->setUser($this);
        }

        return $this;
    }

    public function removeOpinion(Opinion $opinion): self
    {
        if ($this->opinions->removeElement($opinion)) {
            // set the owning side to null (unless already changed)
            if ($opinion->getUser() === $this) {
                $opinion->setUser(null);
            }
        }

        return $this;
    }
    public function getAdmin(){
        return in_array('ROLE_ADMIN', $this->roles);
    }

    /**
     * @return Collection<int, Restaurante>
     */
    public function getRestaurantes(): Collection
    {
        return $this->restaurantes;
    }

    public function addRestaurante(Restaurante $restaurante): self
    {
        if (!$this->restaurantes->contains($restaurante)) {
            $this->restaurantes->add($restaurante);
            $restaurante->setUser($this);
        }

        return $this;
    }

    public function removeRestaurante(Restaurante $restaurante): self
    {
        if ($this->restaurantes->removeElement($restaurante)) {
            // set the owning side to null (unless already changed)
            if ($restaurante->getUser() === $this) {
                $restaurante->setUser(null);
            }
        }

        return $this;
    }
}
