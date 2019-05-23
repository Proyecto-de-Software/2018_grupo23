<?php


namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Paciente
 *
 * @ORM\Table(name="paciente", indexes={@ORM\Index(name="FK_genero_id", columns={"genero_id"})})
 * @ORM\Entity
 */
class Paciente
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
     * @ORM\Column(name="apellido", type="string", length=255, nullable=false)
     */
    private $apellido;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255, nullable=false)
     */
    private $nombre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_nac", type="date", nullable=false)
     */
    private $fechaNac;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lugar_nac", type="string", length=128, nullable=true)
     */
    private $lugarNac;

    /**
     * @var int
     *
     * @ORM\Column(name="localidad_id", type="integer", nullable=false)
     */
    private $localidadId;

    /**
     * @var int
     *
     * @ORM\Column(name="region_sanitaria_id", type="integer", nullable=false)
     */
    private $regionSanitariaId;

    /**
     * @var string
     *
     * @ORM\Column(name="domicilio", type="string", length=255, nullable=false)
     */
    private $domicilio;

    /**
     * @var bool
     *
     * @ORM\Column(name="tiene_documento", type="boolean", nullable=false, options={"default"="1"})
     */
    private $tieneDocumento = '1';

    /**
     * @var int
     *
     * @ORM\Column(name="tipo_doc_id", type="integer", nullable=false)
     */
    private $tipoDocId;

    /**
     * @var int
     *
     * @ORM\Column(name="numero", type="integer", nullable=false)
     */
    private $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=255, nullable=false)
     */
    private $tel;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nro_carpeta", type="integer", nullable=true)
     */
    private $nroCarpeta;

    /**
     * @var int
     *
     * @ORM\Column(name="obra_social_id", type="integer", nullable=false)
     */
    private $obraSocialId;

    /**
     * @var int
     *
     * @ORM\Column(name="partido_id", type="integer", nullable=false)
     */
    private $partidoId;

    /**
     * @var \Genero
     *
     * @ORM\ManyToOne(targetEntity="Genero")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="genero_id", referencedColumnName="id")
     * })
     */
    private $genero;

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
        return $this->fechaNac;
    }

    public function setFechaNac(\DateTimeInterface $fechaNac): self
    {
        $this->fechaNac = $fechaNac;

        return $this;
    }

    public function getLugarNac(): ?string
    {
        return $this->lugarNac;
    }

    public function setLugarNac(?string $lugarNac): self
    {
        $this->lugarNac = $lugarNac;

        return $this;
    }

    public function getLocalidadId(): ?int
    {
        return $this->localidadId;
    }

    public function setLocalidadId(int $localidadId): self
    {
        $this->localidadId = $localidadId;

        return $this;
    }

    public function getRegionSanitariaId(): ?int
    {
        return $this->regionSanitariaId;
    }

    public function setRegionSanitariaId(int $regionSanitariaId): self
    {
        $this->regionSanitariaId = $regionSanitariaId;

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

    public function getTieneDocumento(): ?bool
    {
        return $this->tieneDocumento;
    }

    public function setTieneDocumento(bool $tieneDocumento): self
    {
        $this->tieneDocumento = $tieneDocumento;

        return $this;
    }

    public function getTipoDocId(): ?int
    {
        return $this->tipoDocId;
    }

    public function setTipoDocId(int $tipoDocId): self
    {
        $this->tipoDocId = $tipoDocId;

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
        return $this->nroCarpeta;
    }

    public function setNroCarpeta(?int $nroCarpeta): self
    {
        $this->nroCarpeta = $nroCarpeta;

        return $this;
    }

    public function getObraSocialId(): ?int
    {
        return $this->obraSocialId;
    }

    public function setObraSocialId(int $obraSocialId): self
    {
        $this->obraSocialId = $obraSocialId;

        return $this;
    }

    public function getPartidoId(): ?int
    {
        return $this->partidoId;
    }

    public function setPartidoId(int $partidoId): self
    {
        $this->partidoId = $partidoId;

        return $this;
    }

    public function getGenero(): ?Genero
    {
        return $this->genero;
    }

    public function setGenero(?Genero $genero): self
    {
        $this->genero = $genero;

        return $this;
    }


}
