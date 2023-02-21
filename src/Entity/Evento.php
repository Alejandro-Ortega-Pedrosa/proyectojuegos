<?php

namespace App\Entity;

use App\Repository\EventoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventoRepository::class)]
class Evento
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nombre = null;

    #[ORM\Column(length: 50)]
    private ?string $fecha = null;

    #[ORM\OneToMany(mappedBy: 'evento', targetEntity: Invitacion::class)]
    private Collection $invitacions;

    #[ORM\ManyToOne(inversedBy: 'eventos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Juego $Juego = null;

    public function __construct()
    {
        $this->invitacions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getFecha(): ?string
    {
        return $this->fecha;
    }

    public function setFecha(string $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * @return Collection<int, Invitacion>
     */
    public function getInvitacions(): Collection
    {
        return $this->invitacions;
    }

    public function addInvitacion(Invitacion $invitacion): self
    {
        if (!$this->invitacions->contains($invitacion)) {
            $this->invitacions->add($invitacion);
            $invitacion->setEvento($this);
        }

        return $this;
    }

    public function removeInvitacion(Invitacion $invitacion): self
    {
        if ($this->invitacions->removeElement($invitacion)) {
            // set the owning side to null (unless already changed)
            if ($invitacion->getEvento() === $this) {
                $invitacion->setEvento(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->nombre;
    }

    public function getJuego(): ?Juego
    {
        return $this->Juego;
    }

    public function setJuego(?Juego $Juego): self
    {
        $this->Juego = $Juego;

        return $this;
    }
}
