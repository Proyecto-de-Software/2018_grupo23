<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InstitucionRepository")
 */
class Institucion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $director;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telefono;

    /**
     * @ORM\Column(type="integer")
     */
    private $region_sanitaria_id;

    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $coordenadas;

    /**
     * @ORM\ManyToOne(targetEntity="TipoInstitucion")
     */

    private $tipo;

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

    public function getDirector(): ?string
    {
        return $this->director;
    }

    public function setDirector(string $director): self
    {
        $this->director = $director;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(string $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getRegionSanitariaId(): ?int
    {
        return $this->region_sanitaria_id;
    }

    public function setRegionSanitariaId(int $region_sanitaria_id): self
    {
        $this->region_sanitaria_id = $region_sanitaria_id;

        return $this;
    }

    
    public function getCoordenadas(): ?string
    {
        return $this->coordenadas;
    }

    public function setCoordenadas(string $coordenadas): self
    {
        $this->coordenadas = $coordenadas;

        return $this;
    }
    
    public function getTipo()
    {
        return $this->tipo;
    }

    public function setAcompanamiento($tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }

}
