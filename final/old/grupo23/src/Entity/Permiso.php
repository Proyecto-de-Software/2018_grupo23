<?php


namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Permiso
 *
 * @ORM\Table(name="permiso")
 * @ORM\Entity
 */
class Permiso
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255, nullable=false)
     */
    private $nombre;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Role", mappedBy="permiso")
     * 
     */
    private $rol;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rol = new \Doctrine\Common\Collections\ArrayCollection();
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

    /**
     * @return Collection|Role[]
     */
    public function getRol(): Collection
    {
        return $this->rol;
        //return new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function addRol(Role $rol): self
    {
        if (!$this->rol->contains($rol)) {
            $this->rol[] = $rol;
            $rol->addPermiso($this);
        }

        return $this;
    }

    public function removeRol(Role $rol): self
    {
        if ($this->rol->contains($rol)) {
            $this->rol->removeElement($rol);
            $rol->removePermiso($this);
        }

        return $this;
    }

}
