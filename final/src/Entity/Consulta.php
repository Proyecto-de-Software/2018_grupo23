<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ConsultaRepository")
 */
class Consulta
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\paciente", inversedBy="consultas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $paciente;

    /**
     * @ORM\Column(type="date")
     */
    private $fecha;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\motivoConsulta")
     * @ORM\JoinColumn(nullable=false)
     */
    private $motivo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\institucion")
     */
    private $derivacion;

    /**
     * @ORM\Column(type="string", length=190, nullable=true)
     */
    private $articulacion_con_insituciones;

    /**
     * @ORM\Column(type="boolean")
     */
    private $internacion;

    /**
     * @ORM\Column(type="string", length=190, nullable=true)
     */
    private $diagnostico;

    /**
     * @ORM\Column(type="string", length=190, nullable=true)
     */
    private $observaciones;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\tratamientoFarmacologico")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tratamiento_farmacologico;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\acompanamiento")
     * @ORM\JoinColumn(nullable=false)
     */
    private $acompanamiento;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPacienteId()
    {
        return $this->paciente;
    }

    public function setPacienteId(?paciente $paciente): self
    {
        $this->paciente = $paciente;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getMotivoId()
    {
        return $this->motivo;
    }

    public function setMotivoId(?motivoConsulta $motivo): self
    {
        $this->motivo = $motivo;

        return $this;
    }

    public function getDerivacionId()
    {
        return $this->derivacion;
    }

    public function setDerivacionId(?institucion $derivacion): self
    {
        $this->derivacion = $derivacion;

        return $this;
    }

    public function getArticulacionConInsituciones(): ?string
    {
        return $this->articulacion_con_insituciones;
    }

    public function setArticulacionConInsituciones(?string $articulacion_con_insituciones): self
    {
        $this->articulacion_con_insituciones = $articulacion_con_insituciones;

        return $this;
    }

    public function getInternacion(): ?bool
    {
        return $this->internacion;
    }

    public function setInternacion(bool $internacion): self
    {
        $this->internacion = $internacion;

        return $this;
    }

    public function getDiagnostico(): ?string
    {
        return $this->diagnostico;
    }

    public function setDiagnostico(?string $diagnostico): self
    {
        $this->diagnostico = $diagnostico;

        return $this;
    }

    public function getObservaciones(): ?string
    {
        return $this->observaciones;
    }

    public function setObservaciones(?string $observaciones): self
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    public function getTratamientoFarmacologicoId()
    {
        return $this->tratamiento_farmacologico;
    }

    public function setTratamientoFarmacologicoId(?tratamientoFarmacologico $tratamiento_farmacologico): self
    {
        $this->tratamiento_farmacologico = $tratamiento_farmacologico;

        return $this;
    }

    public function getAcompanamientoId()
    {
        return $this->acompanamiento;
    }

    public function setAcompanamientoId(?acompanamiento $acompanamiento): self
    {
        $this->acompanamiento = $acompanamiento;

        return $this;
    }
}
