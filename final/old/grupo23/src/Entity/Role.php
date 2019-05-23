<?php


namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Rol
 *
 * @ORM\Table(name="rol")
 * @ORM\Entity(repositoryClass="App\Repository\RoleRepository")
 */
class Role
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
     * @ORM\ManyToMany(targetEntity="Permiso", inversedBy="rol", fetch = "LAZY")
     * @ORM\JoinTable(name="rol_tiene_permiso",
     *   joinColumns={
     *     @ORM\JoinColumn(name="rol_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="permiso_id", referencedColumnName="id")
     *   }
     * )
     */
    private $permiso;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Usuario", mappedBy="roles")
     *
     */
    private $usuario;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->permiso = new \Doctrine\Common\Collections\ArrayCollection();
        $this->usuario = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function getRole(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * @return Collection|Permiso[]
     */
    public function getPermiso(): Collection
    {
        return $this->permiso;
    }

    public function addPermiso(Permiso $permiso): self
    {
        if (!$this->permiso->contains($permiso)) {
            $this->permiso[] = $permiso;
        }

        return $this;
    }

    public function removePermiso(Permiso $permiso): self
    {
        if ($this->permiso->contains($permiso)) {
            $this->permiso->removeElement($permiso);
        }

        return $this;
    }

    /**
     * @return Collection|Usuario[]
     */
    public function getUsuario(): Collection
    {
        return $this->usuario;
        //return new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function addUsuario(Usuario $usuario): self
    {
        if (!$this->usuario->contains($usuario)) {
            $this->usuario[] = $usuario;
            $usuario->addRol($this);
        }

        return $this;
    }

    public function removeUsuario(Usuario $usuario): self
    {
        if ($this->usuario->contains($usuario)) {
            $this->usuario->removeElement($usuario);
            $usuario->removeRol($this);
        }

        return $this;
    }


}
