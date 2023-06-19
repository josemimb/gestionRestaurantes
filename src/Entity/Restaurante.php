<?php

namespace App\Entity;

use App\Repository\RestauranteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Vich\UploaderBundle\Form\Type\VichImageType;

#[ORM\Entity(repositoryClass: RestauranteRepository::class)]
#[Vich\Uploadable]
class Restaurante
{
    public function __toString(): string
    {
        return $this->getNombre();
    }
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $nombre = null;

    #[ORM\Column(length: 60, nullable: true)]
    private ?string $Direccion = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imagenRestaurante = null;

    #[Vich\UploadableField(mapping: 'restaurante', fileNameProperty: 'imagenRestaurante')]
    private $imageFile;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imagenCarta = null;

    #[Vich\UploadableField(mapping: 'restaurante', fileNameProperty: 'imagenCarta')]
    private $imageFileCarta;

    #[ORM\Column(nullable: true)]
    private ?int $contacto = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $horario = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descripcion = null;

    #[ORM\OneToMany(mappedBy: 'restaurante', targetEntity: Reserva::class)]
    private Collection $reservas;

    #[ORM\OneToMany(mappedBy: 'restaurante', targetEntity: User::class)]
    private Collection $users;

    #[ORM\ManyToOne(inversedBy: 'restaurantes')]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'restaurante', targetEntity: Opinion::class)]
    private Collection $opinions;

    #[ORM\OneToMany(mappedBy: 'restaurante', targetEntity: Mesa::class)]
    private Collection $mesas;

    public function __construct()
    {
        $this->reservas = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->opinions = new ArrayCollection();
        $this->mesas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDireccion(): ?string
    {
        return $this->Direccion;
    }

    public function setDireccion(?string $Direccion): self
    {
        $this->Direccion = $Direccion;

        return $this;
    }

    public function getImagenRestaurante(): ?string
    {
        return $this->imagenRestaurante;
    }

    public function setImagenRestaurante(?string $imagenRestaurante): self
    {
        $this->imagenRestaurante = $imagenRestaurante;

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }


    public function setImageFile(?File $imageFile=null)
    {
        $this->imageFile = $imageFile;

        return $this;
    }

    public function getImagenCarta(): ?string
    {
        return $this->imagenCarta;
    }

    public function setImagenCarta(?string $imagenCarta): self
    {
        $this->imagenCarta = $imagenCarta;

        return $this;
    }

    public function getImageFileCarta(): ?File
    {
        return $this->imageFileCarta;
    }


    public function setImageFileCarta(?File $imageFileCarta=null)
    {
        $this->imageFileCarta = $imageFileCarta;

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

    public function getHorario(): ?string
    {
        return $this->horario;
    }

    public function setHorario(?string $horario): self
    {
        $this->horario = $horario;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

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
            $reserva->setRestaurante($this);
        }

        return $this;
    }

    public function removeReserva(Reserva $reserva): self
    {
        if ($this->reservas->removeElement($reserva)) {
            // set the owning side to null (unless already changed)
            if ($reserva->getRestaurante() === $this) {
                $reserva->setRestaurante(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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
            $opinion->setRestaurante($this);
        }

        return $this;
    }

    public function removeOpinion(Opinion $opinion): self
    {
        if ($this->opinions->removeElement($opinion)) {
            // set the owning side to null (unless already changed)
            if ($opinion->getRestaurante() === $this) {
                $opinion->setRestaurante(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Mesa>
     */
    public function getMesas(): Collection
    {
        return $this->mesas;
    }

    public function addMesa(Mesa $mesa): self
    {
        if (!$this->mesas->contains($mesa)) {
            $this->mesas->add($mesa);
            $mesa->setRestaurante($this);
        }

        return $this;
    }

    public function removeMesa(Mesa $mesa): self
    {
        if ($this->mesas->removeElement($mesa)) {
            // set the owning side to null (unless already changed)
            if ($mesa->getRestaurante() === $this) {
                $mesa->setRestaurante(null);
            }
        }

        return $this;
    }
}
