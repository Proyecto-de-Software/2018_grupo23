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
     * @ORM\Column(type="date")
     */
    private $fecha;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $articulacion_con_instituciones;

    /**
     * @ORM\Column(type="boolean")
     */
    private $internacion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $diagnostico;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $observaciones;

     /**
     * @ORM\ManyToOne(targetEntity="Paciente",inversedBy="consultas")
     */

    private $paciente;

    /**
     * @ORM\ManyToOne(targetEntity="MotivoConsulta")
     */

    private $motivo;

    /**
     * @ORM\ManyToOne(targetEntity="Institucion")
     */

    private $derivacion;

    /**
     * @ORM\ManyToOne(targetEntity="TratamientoFarmacologico")
     */

    private $tratamiento_farmacologico;

    /**
     * @ORM\ManyToOne(targetEntity="Acompanamiento")
     */

    private $acompanamiento;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getArticulacionConInstituciones(): ?string
    {
        return $this->articulacion_con_instituciones;
    }

    public function setArticulacionConInstituciones(?string $articulacion_con_instituciones): self
    {
        $this->articulacion_con_instituciones = $articulacion_con_instituciones;

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

    
    public function getPaciente()
    {
        return $this->paciente;
    }

    public function setPaciente($paciente): self
    {
        $this->paciente = $paciente;

        return $this;
    }

    public function getMotivo()
    {
        return $this->motivo;
    }

    public function setMotivo($motivo): self
    {
        $this->motivo = $motivo;

        return $this;
    }

    public function getDerivacion()
    {
        return $this->derivacion;
    }

    public function setDerivacion($derivacion): self
    {
        $this->derivacion = $derivacion;

        return $this;
    }

    public function getTratamientoFarmacologico()
    {
        return $this->tratamiento_farmacologico;
    }

    public function setTratamientoFarmacologico($tratamiento_farmacologico): self
    {
        $this->tratamiento_farmacologico = $tratamiento_farmacologico;

        return $this;
    }

    public function getAcompanamiento()
    {
        return $this->acompanamiento;
    }

    public function setAcompanamiento($acompanamiento): self
    {
        $this->acompanamiento = $acompanamiento;

        return $this;
    }
}
