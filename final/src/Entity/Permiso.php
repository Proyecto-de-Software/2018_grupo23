<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PermisoRepository")
 */
class Permiso
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nombre;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\RolesDelSistema", inversedBy="permisos")
     */
    private $roles;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
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
     * @return Collection|RolesDelSistema[]
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function addRole(RolesDelSistema $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole(RolesDelSistema $role): self
    {
        if ($this->roles->contains($role)) {
            $this->roles->removeElement($role);
        }

        return $this;
    }

    public function hasRole($rol_name): bool
    {  
        $ok = false;
        $rs = $this->getRoles();
        $i = 0;
        while(!$ok || sizeof($rs) > $i){
            $r = $rs[$i];
            $ok = $r->getNombre() === $rol_name;
            $i++;
        }
        return $ok;
    }
}
