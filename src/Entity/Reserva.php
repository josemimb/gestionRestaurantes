<?php

namespace App\Entity;

use App\Repository\ReservaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservaRepository::class)]
class Reserva
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 40, nullable: true)]
    private ?string $fechaHora = null;
    
    #[ORM\Column(length: 40, nullable: true)]
    #[Assert\NotNull(message: 'Por favor, seleccione una hora.')]
    private ?string $hora = null;

    #[ORM\Column(length: 40, nullable: true)]
    private ?string $lugar = null;


    #[ORM\ManyToOne(inversedBy: 'reservas')]
    #[Assert\NotNull(message: 'Por favor, seleccione una mesa.')]
    private ?Mesa $mesa = null;

    #[ORM\ManyToOne(inversedBy: 'reservas')]
    private ?User $usuario = null;

    #[ORM\ManyToOne(inversedBy: 'reservas')]
    #[ORM\JoinColumn(name: 'restaurante_id', referencedColumnName:'id')]
    private ?Restaurante $restaurante = null;

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFechaHora(): ?string
    {
        return $this->fechaHora;
    }

    public function setFechaHora(?string $fechaHora): self
    {
        $this->fechaHora = $fechaHora;

        return $this;
    }
    public function getHora(): ?string
    {
        return $this->hora;
    }

    public function setHora(?string $hora): self
    {
        $this->hora = $hora;

        return $this;
    }

    public function getLugar(): ?string
    {
        return $this->lugar;
    }

    public function setLugar(?string $lugar): self
    {
        $this->lugar = $lugar;

        return $this;
    }


    public function getMesa(): ?Mesa
    {
        return $this->mesa;
    }

    public function setMesa(?Mesa $mesa): self
    {
        $this->mesa = $mesa;

        return $this;
    }

    public function getUsuario(): ?User
    {
        return $this->usuario;
    }

    public function setUsuario(?User $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getRestaurante(): ?Restaurante
    {
        return $this->restaurante;
    }

    public function setRestaurante(?Restaurante $restaurante): self
    {
        $this->restaurante = $restaurante;

        return $this;
    }

    
}
