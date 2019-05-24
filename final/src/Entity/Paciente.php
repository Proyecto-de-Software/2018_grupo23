<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PacienteRepository")
 */
class Paciente
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=190)
     */
    private $apellido;

    /**
     * @ORM\Column(type="string", length=190)
     */
    private $nombre;

    /**
     * @ORM\Column(type="date")
     */
    private $fecha_nac;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     */
    private $lugar_nac;

    /**
     * @ORM\Column(type="integer")
     */
    private $localidad_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $region_sanitaria_id;

    /**
     * @ORM\Column(type="string", length=190)
     */
    private $domicilio;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\genero")
     * @ORM\JoinColumn(nullable=false)
     */
    private $genero;

    /**
     * @ORM\Column(type="boolean")
     */
    private $tiene_documento;

    /**
     * @ORM\Column(type="integer")
     */
    private $tipo_doc_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $numero;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $tel;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nro_carpeta;

    /**
     * @ORM\Column(type="integer")
     */
    private $obra_social_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $partido_id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Consulta", mappedBy="paciente", orphanRemoval=true)
     */
    private $consultas;

    public function __construct()
    {
        $this->consultas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApellido(): ?string
    {
        return $this->apellido;
    }

    public function setApellido(string $apellido): self
    {
        $this->apellido = $apellido;

        return $this;
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

    public function getFechaNac(): ?\DateTimeInterface
    {
        return $this->fecha_nac;
    }

    public function setFechaNac(\DateTimeInterface $fecha_nac): self
    {
        $this->fecha_nac = $fecha_nac;

        return $this;
    }

    public function getLugarNac(): ?string
    {
        return $this->lugar_nac;
    }

    public function setLugarNac(?string $lugar_nac): self
    {
        $this->lugar_nac = $lugar_nac;

        return $this;
    }

    public function getLocalidadId(): ?int
    {
        return $this->localidad_id;
    }

    public function setLocalidadId(int $localidad_id): self
    {
        $this->localidad_id = $localidad_id;

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

    public function getDomicilio(): ?string
    {
        return $this->domicilio;
    }

    public function setDomicilio(string $domicilio): self
    {
        $this->domicilio = $domicilio;

        return $this;
    }

    public function getGeneroId()
    {
        return $this->genero;
    }

    public function setGeneroId(?genero $genero): self
    {
        $this->genero = $genero;

        return $this;
    }

    public function getTieneDocumento(): ?bool
    {
        return $this->tiene_documento;
    }

    public function setTieneDocumento(bool $tiene_documento): self
    {
        $this->tiene_documento = $tiene_documento;

        return $this;
    }

    public function getTipoDocId(): ?int
    {
        return $this->tipo_doc_id;
    }

    public function setTipoDocId(int $tipo_doc_id): self
    {
        $this->tipo_doc_id = $tipo_doc_id;

        return $this;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getNroCarpeta(): ?int
    {
        return $this->nro_carpeta;
    }

    public function setNroCarpeta(?int $nro_carpeta): self
    {
        $this->nro_carpeta = $nro_carpeta;

        return $this;
    }

    public function getObraSocialId(): ?int
    {
        return $this->obra_social_id;
    }

    public function setObraSocialId(int $obra_social_id): self
    {
        $this->obra_social_id = $obra_social_id;

        return $this;
    }

    public function getPartidoId(): ?int
    {
        return $this->partido_id;
    }

    public function setPartidoId(int $partido_id): self
    {
        $this->partido_id = $partido_id;

        return $this;
    }

    /**
     * @return Collection|Consulta[]
     */
    public function getConsultas(): Collection
    {
        return $this->consultas;
    }

    public function addConsulta(Consulta $consulta): self
    {
        if (!$this->consultas->contains($consulta)) {
            $this->consultas[] = $consulta;
            $consulta->setPacienteId($this);
        }

        return $this;
    }

    public function removeConsulta(Consulta $consulta): self
    {
        if ($this->consultas->contains($consulta)) {
            $this->consultas->removeElement($consulta);
            // set the owning side to null (unless already changed)
            if ($consulta->getPacienteId() === $this) {
                $consulta->setPacienteId(null);
            }
        }

        return $this;
    }
}
