<?php


namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Institucion
 *
 * @ORM\Table(name="institucion", indexes={@ORM\Index(name="FK_institucion_region_sanitaria_id", columns={"region_sanitaria_id"}), @ORM\Index(name="FK_tipo_institucion_id", columns={"tipo_institucion_id"})})
 * @ORM\Entity
 */
class Institucion
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
     * @var string
     *
     * @ORM\Column(name="director", type="string", length=255, nullable=false)
     */
    private $director;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=255, nullable=false)
     */
    private $telefono;

    /**
     * @var int
     *
     * @ORM\Column(name="region_sanitaria_id", type="integer", nullable=false)
     */
    private $regionSanitariaId;

    // /**
    //  * @var string
    //  *
    //  * @ORM\Column(name="coordenadas", type="string", length=50, nullable=false)
    //  */
    // private $coordenadas;

    /**
     * @var \TipoInstitucion
     *
     * @ORM\ManyToOne(targetEntity="TipoInstitucion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tipo_institucion_id", referencedColumnName="id")
     * })
     */
    private $tipoInstitucion;

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
        return $this->regionSanitariaId;
    }

    public function setRegionSanitariaId(int $regionSanitariaId): self
    {
        $this->regionSanitariaId = $regionSanitariaId;

        return $this;
    }

    // public function getCoordenadas(): ?string
    // {
    //     return $this->coordenadas;
    // }
    //
    // public function setCoordenadas(string $coordenadas): self
    // {
    //     $this->coordenadas = $coordenadas;
    //
    //     return $this;
    // }

    public function getTipoInstitucion(): ?TipoInstitucion
    {
        return $this->tipoInstitucion;
    }

    public function setTipoInstitucion(?TipoInstitucion $tipoInstitucion): self
    {
        $this->tipoInstitucion = $tipoInstitucion;

        return $this;
    }


}
