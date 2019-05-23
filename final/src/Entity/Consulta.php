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
    private $paciente_id;

    /**
     * @ORM\Column(type="date")
     */
    private $fecha;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\motivoConsulta")
     * @ORM\JoinColumn(nullable=false)
     */
    private $motivo_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\institucion")
     */
    private $derivacion_id;

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
    private $tratamiento_farmacologico_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\acompanamiento")
     * @ORM\JoinColumn(nullable=false)
     */
    private $acompanamiento_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPacienteId(): ?paciente
    {
        return $this->paciente_id;
    }

    public function setPacienteId(?paciente $paciente_id): self
    {
        $this->paciente_id = $paciente_id;

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

    public function getMotivoId(): ?motivoConsulta
    {
        return $this->motivo_id;
    }

    public function setMotivoId(?motivoConsulta $motivo_id): self
    {
        $this->motivo_id = $motivo_id;

        return $this;
    }

    public function getDerivacionId(): ?institucion
    {
        return $this->derivacion_id;
    }

    public function setDerivacionId(?institucion $derivacion_id): self
    {
        $this->derivacion_id = $derivacion_id;

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

    public function getTratamientoFarmacologicoId(): ?tratamientoFarmacologico
    {
        return $this->tratamiento_farmacologico_id;
    }

    public function setTratamientoFarmacologicoId(?tratamientoFarmacologico $tratamiento_farmacologico_id): self
    {
        $this->tratamiento_farmacologico_id = $tratamiento_farmacologico_id;

        return $this;
    }

    public function getAcompanamientoId(): ?acompanamiento
    {
        return $this->acompanamiento_id;
    }

    public function setAcompanamientoId(?acompanamiento $acompanamiento_id): self
    {
        $this->acompanamiento_id = $acompanamiento_id;

        return $this;
    }
}
