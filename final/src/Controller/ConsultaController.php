<?php

namespace App\Controller;

use App\Entity\Consulta;
use App\Entity\Paciente;
use App\Entity\Permiso;
use App\Entity\Acompanamiento;
use App\Entity\Institucion;
use App\Entity\MotivoConsulta;
use App\Entity\TratamientoFarmacologico;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;


/**
* Class ConsultaController
*
* @Route("/consulta")
*/

class ConsultaController extends FOSRestController
{


    /**
     *@Route("/index/{id}", name="consulta_index", methods={"GET"})
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="Consulta")
     */
    public function index(Request $request, $id) : response
    {
        $serializer = $this->get('jms_serializer');
        $entityManager = $this->getDoctrine()->getManager();
        if ($this->getUser()->hasPermit($entityManager->getRepository(Permiso::class)->findOneBy(['nombre' => 'atencion_show']))) {
          $querys = $this->getDoctrine()->getRepository(Consulta::class)->findBy(['paciente' => $id]);
          return new Response($serializer->serialize($querys, "json"));
        }
    }

    /**
     * @Route("/new/{idPaciente}", name="consulta_new", methods={"POST"})
     * @SWG\Response(response=200, description="Consulta was created successfully")
     * @SWG\Tag(name="Consulta")
     * @RequestParam(name="fecha", strict=true, nullable=false, allowBlank=false, description="Fecha.")
     * @RequestParam(name="motivo", strict=true, nullable=false, allowBlank=false, description="Motivo.")
     * @RequestParam(name="acompanamiento", allowBlank=true, description="Acompañamiento.")
     * @RequestParam(name="derivacion", allowBlank=true, description="Derivacion.")
     * @RequestParam(name="diagnostico", strict=true, nullable=false, allowBlank=false, description="Diagnostico.")
     * @RequestParam(name="internacion", strict=true, nullable=false, allowBlank=false, description="Internacion.")
     * @RequestParam(name="observaciones", allowBlank=true, description="Observaciones generales.")
     * @RequestParam(name="tratamiento", allowBlank=true, description="Tratamiento Farmacológico.")
     * @RequestParam(name="articulacion", allowBlank=true, description="Articulación.")
     * 
     * @param ParamFetcher $pf
     */
    public function new(Request $request, ParamFetcher $pf, $idPaciente): Response
    {
      $entityManager = $this->getDoctrine()->getManager();
      if ($this->getUser()->hasPermit($entityManager->getRepository(Permiso::class)->findOneBy(['nombre' => 'atencion_new']))) {
        $motivo=$entityManager->getRepository(MotivoConsulta::class)->findOneBy(['id'=>$pf->get('motivo')]);
        $acompanamiento=$entityManager->getRepository(Acompanamiento::class)->findOneBy(['id'=>$pf->get('acompanamiento')]);
        $derivacion=$entityManager->getRepository(Institucion::class)->findOneBy(['id'=>$pf->get('derivacion')]);
        $tratamiento=$entityManager->getRepository(TratamientoFarmacologico::class)->findOneBy(['id'=>$pf->get('tratamiento')]);
        $paciente=$entityManager->getRepository(Paciente::class)->findOneBy(['id'=>$idPaciente]);
        $consulta= new Consulta();
        $consulta->setFecha(new \DateTime('@'.strtotime($pf->get('fecha'))));
        $consulta->setMotivoId($motivo);
        $consulta->setAcompanamientoId($acompanamiento);
        $consulta->setDerivacionId($derivacion);
        $consulta->setDiagnostico($pf->get('diagnostico'));
        $consulta->setInternacion($pf->get('internacion'));
        $consulta->setObservaciones($pf->get('observaciones'));
        $consulta->setTratamientoFarmacologicoId($tratamiento);
        $consulta->setPacienteId($paciente);
        $entityManager->persist($consulta);
        $entityManager->flush();
        return new Response('Atención agregada', 200);
      } else {
        return new Response("No tienes permiso para realizar esa acción", 400);
      }
    }

    /**
     * @Route("/{id}/edit", name="consulta_edit", methods={"POST"})
     * @SWG\Response(response=200, description="Consulta was edited succesfully")
     * @SWG\Tag(name="Consulta")
     * @RequestParam(name="fecha", strict=true, nullable=false, allowBlank=false, description="Fecha.")
     * @RequestParam(name="motivo", strict=true, nullable=false, allowBlank=false, description="Motivo.")
     * @RequestParam(name="acompanamiento", allowBlank=true, description="Acompañamiento.")
     * @RequestParam(name="derivacion", allowBlank=true, description="Derivacion.")
     * @RequestParam(name="diagnostico", strict=true, nullable=false, allowBlank=false, description="Diagnostico.")
     * @RequestParam(name="internacion", strict=true, nullable=false, allowBlank=false, description="Internacion.")
     * @RequestParam(name="observaciones", allowBlank=true, description="Observaciones generales.")
     * @RequestParam(name="tratamiento", allowBlank=true, description="Tratamiento Farmacológico.")
     * @RequestParam(name="articulacion", allowBlank=true, description="Articulación.")
     * 
     * @param ParamFetcher $pf
     */
    public function edit(Request $request, ParamFetcher $pf, $id): Response
    {
      $entityManager = $this->getDoctrine()->getManager();
      if ($this->getUser()->hasPermit($entityManager->getRepository(Permiso::class)->findOneBy(['nombre' => 'atencion_update']))) {
        $motivo=$entityManager->getRepository(MotivoConsulta::class)->findOneBy(['id'=>$pf->get('motivo')]);
        $acompanamiento=$entityManager->getRepository(Acompanamiento::class)->findOneBy(['id'=>$pf->get('acompanamiento')]);
        $derivacion=$entityManager->getRepository(Institucion::class)->findOneBy(['id'=>$pf->get('derivacion')]);
        $tratamiento=$entityManager->getRepository(TratamientoFarmacologico::class)->findOneBy(['id'=>$pf->get('tratamiento')]);
        $consulta=$entityManager->getRepository(Consulta::class)->findOneBy(['id'=>$id]);
        $consulta->setFecha(new \DateTime('@'.strtotime($pf->get('fecha'))));
        $consulta->setMotivoId($motivo);
        $consulta->setAcompanamientoId($acompanamiento);
        $consulta->setDerivacionId($derivacion);
        $consulta->setDiagnostico($pf->get('diagnostico'));
        $consulta->setInternacion($pf->get('internacion'));
        $consulta->setObservaciones($pf->get('observaciones'));
        $consulta->setTratamientoFarmacologicoId($tratamiento);
        $entityManager->persist($consulta);
        $entityManager->flush();
        return new Response('Atención agregada', 200);
      } else {
        return new Response("No tienes permiso para realizar esa acción", 400);
      }
    }


    /**
     *@Route("/reportes/motivo", name="reporte_consulta_motivo", methods={"GET"})
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="Consulta")
     */
    public function viewAttentionsByReason(): Response
    {
      $serializer = $this->get('jms_serializer');
      $attentions = $this->getDoctrine()->getRepository(Consulta::class)->findByReason();
      return new Response($serializer->serialize($attentions, "json"));
    }

    /**
     *@Route("/reportes/genero", name="reporte_consulta_genero", methods={"GET"})
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="Consulta")
     */
    public function viewAttentionsByGenre(): Response
    {
      $serializer = $this->get('jms_serializer');
      $attentions = $this->getDoctrine()->getRepository(Consulta::class)->findByGenre();
      return new Response($serializer->serialize($attentions, "json"));
    }

    /**
     *@Route("/reportes/localidad", name="reporte_consulta_localidad", methods={"GET"})
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="Consulta")
     */
    public function viewAttentionsByLocation(): Response
    {
      $serializer = $this->get('jms_serializer');
      $attentions = $this->getDoctrine()->getRepository(Consulta::class)->findByLocation();
      return new Response($serializer->serialize($attentions, "json"));
    }

    /**
     *@Route("/acompanamientos", name="acompanamientos", methods={"GET"})
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="Consulta")
     */
    public function acompanamientos(): Response
    {
      $serializer = $this->get('jms_serializer');
      $acompanamientos = $this->getDoctrine()->getRepository(Acompanamiento::class)->findAll();
      return new Response($serializer->serialize($acompanamientos, "json"));
    }

    /**
     *@Route("/instituciones", name="instituciones", methods={"GET"})
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="Consulta")
     */
    public function instituciones(): Response
    {
      $serializer = $this->get('jms_serializer');
      $instituciones = $this->getDoctrine()->getRepository(Institucion::class)->findAll();
      return new Response($serializer->serialize($instituciones, "json"));
    }

    /**
     *@Route("/motivos", name="motivos", methods={"GET"})
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="Consulta")
     */
    public function motivos(): Response
    {
      $serializer = $this->get('jms_serializer');
      $motivos = $this->getDoctrine()->getRepository(MotivoConsulta::class)->findAll();
      return new Response($serializer->serialize($motivos, "json"));
    }

    /**
     *@Route("/tratamientos", name="tratamientos", methods={"GET"})
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="Consulta")
     */
    public function tratamientos(): Response
    {
      $serializer = $this->get('jms_serializer');
      $tratamientos = $this->getDoctrine()->getRepository(TratamientoFarmacologico::class)->findAll();
      return new Response($serializer->serialize($tratamientos, "json"));
    }

}
