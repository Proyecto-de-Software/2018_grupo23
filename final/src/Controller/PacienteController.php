<?php

namespace App\Controller;

use App\Entity\Paciente;
use App\Entity\Genero;
use App\Entity\Permiso;
use App\Entity\RolesDelSistema;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

/**
 * Class Paciente   Controller
 *
 * @Route("/paciente")
 */
class PacienteController extends FOSRestController
{


    /**
     *@Route("/index", name="paciente_index", methods={"GET"})
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="Patient")
     */
    public function index(Request $request)
    {
        $serializer = $this->get('jms_serializer');
        $patients = $this->getDoctrine()->getRepository(Paciente::class)->findAll();
        return new Response($serializer->serialize($patients, "json"));
    }

    /**
     *@Route("/generos", name="paciente_generos", methods={"GET"})
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="Patient")
     */
    public function generos(Request $request)
    {
        $serializer = $this->get('jms_serializer');
        $generos = $this->getDoctrine()->getRepository(Genero::class)->findAll();
        return new Response($serializer->serialize($generos, "json"));
    }
    
    /**
     * @Route("/new", name="paciente_new", methods={"POST"})
     * @SWG\Response(response=200, description="Patient was created successfully")
     * @SWG\Tag(name="Patient")
     * @RequestParam(name="apellido", strict=true, nullable=false, allowBlank=false, description="Apellido.")
     * @RequestParam(name="nombre", strict=true, nullable=false, allowBlank=false, description="Nombre.")
     * @RequestParam(name="fechaNac", strict=true, nullable=false, allowBlank=false, description="Fecha de nacimiento.")
     * @RequestParam(name="lugarNac", strict=false, nullable=true, allowBlank=true, description="Lugar de nacimiento.")
     * @RequestParam(name="partidoId", description="Partido.")
     * @RequestParam(name="regionSanitariaId", description="Region Sanitaria.")
     * @RequestParam(name="localidadId", description="Localidad.")
     * @RequestParam(name="domicilio", strict=true, nullable=false, allowBlank=false, description="Domicilio.")
     * @RequestParam(name="genero", strict=true, nullable=false, allowBlank=false, description="Genero.")
     * @RequestParam(name="tieneDocumento", strict=true, nullable=false, allowBlank=false, description="Tiene documento?.")
     * @RequestParam(name="tipoDocId", strict=true, nullable=false, allowBlank=false, description="Tipo de Documento.")
     * @RequestParam(name="numero", strict=true, nullable=false, allowBlank=false, description="Número de documento.")
     * @RequestParam(name="tel",  description="Telefono.")
     * @RequestParam(name="nroCarpeta",  description="Número de carpeta.")
     * @RequestParam(name="obraSocialId",  description="Obra Social.")
     * @RequestParam(name="historiaClinica",  description="Historia Clínica.")
     * 
     * @param ParamFetcher $pf
     */
    public function new(Request $request, ParamFetcher $pf): Response
    {
      $entityManager = $this->getDoctrine()->getManager();
      if ($this->getUser()->hasPermit($entityManager->getRepository(Permiso::class)->findOneBy(['nombre' => 'paciente_new']))) {
        if(
            $this->isValidId("https://api-referencias.proyecto2018.linti.unlp.edu.ar/partido",$pf->get('partidoId')) &&
            $this->isValidId("https://api-referencias.proyecto2018.linti.unlp.edu.ar/region-sanitaria",$pf->get('regionSanitariaId')) &&
            $this->isValidId("https://api-referencias.proyecto2018.linti.unlp.edu.ar/localidad",$pf->get('localidadId')) &&
            $this->isValidId("https://api-referencias.proyecto2018.linti.unlp.edu.ar/obra-social",$pf->get('obraSocialId')) &&
            $this->isValidId("https://api-referencias.proyecto2018.linti.unlp.edu.ar/tipo-documento",$pf->get('tipoDocId')) &&
            $this->checkDoc($pf->get('numero'))
        ){
        $paciente= new Paciente();
        $paciente->setApellido($pf->get('apellido'));
        $paciente->setNombre($pf->get('nombre'));
        $paciente->setFechaNac($pf->get('fechaNac'));
        $paciente->setLugarNac($pf->get('lugarNac'));
        $paciente->setPartidoId($pf->get('partidoId'));
        $paciente->setRegionSanitariaId($pf->get('regionSanitariaId'));
        $paciente->setLocalidadId($pf->get('localidadId'));
        $paciente->setDomicilio($pf->get('domicilio'));
        $paciente->setGenero($pf->get('genero'));
        $paciente->setTieneDocumento($pf->get('tieneDocumento'));
        $paciente->setTipoDocId($pf->get('tipoDocId'));
        $paciente->setNumero($pf->get('numero'));
        $paciente->setTel($pf->get('tel'));
        $paciente->setNroCarpeta('nroCarpeta');
        $paciente->setObraSocialId('obraSocialId');
        $entityManager->persist($user);
        $entityManager->flush();
        return new Response('Usuario agregado', 200);
        }
        else {
            return new Response("Algo ha salido mal", 400);
        }
      } else {
        return new Response("No tienes permiso para realizar esa acción", 400);
      }
    }

    function isValidId($url,$id){
        if(!empty($id)){
          $data=json_decode(@file_get_contents($url),true);
          for($a=0;$a<count($data);$a++){
          if(in_array($id,$data[$a])){
            return True;
          }
        }
        return False;
        }else {
          return True; //si el id esta vacio paso true ya que no puedo chequear que un campo nulo no pertenezca al conjunto de la API
        }
      }
    function checkDate($date){
        return $date<date('Y-m-d');
    }
    function checkDoc($doc){
        return isEmpty($entityManager->getRepository(Paciente::class)->findOneBy(['numero' => $doc]));

    }
    
    
}
