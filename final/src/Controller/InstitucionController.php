<?php

namespace App\Controller;

use App\Entity\Institucion;
use App\Entity\TipoInstitucion;
use App\Entity\Permiso;
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
 * @Route("/institucion")
 */
class InstitucionController extends FOSRestController
{
    /**
     * @Route("/", name="institucion_index", methods={"GET"})
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="Institucion")
     */
    public function index()
    {
      $entityManager = $this->getDoctrine()->getManager();
      $serializer = $this->get('jms_serializer');
      $ins = $this->getDoctrine()->getRepository(Institucion::class)->findAll();
      return new Response($serializer->serialize($ins, "json"), 200);
    }
     /**
     * @Route("/new", name="institucion_new", methods={"POST"})
     * @SWG\Response(response=200, description="Institution was created successfully")
     * @SWG\Tag(name="Institution")
     * @RequestParam(name="nombre", strict=true, nullable=false, allowBlank=false, description="Nombre.")
     * @RequestParam(name="director", strict=true, nullable=false, allowBlank=false, description="Director.")
     * @RequestParam(name="telefono", strict=true, nullable=false, allowBlank=false, description="Teléfono.")
     * @RequestParam(name="tipoInstitucionId",strict=true, nullable=false, allowBlank=false, description="Tipo de institucion.")
     * @RequestParam(name="regionSanitariaId",strict=true, nullable=false, allowBlank=false, description="Region Sanitaria.")
     * @RequestParam(name="coordenadas", strict=true, nullable=false, allowBlank=false, description="Coordenadas.")
     * 
     * @param ParamFetcher $pf
     */
    public function new(Request $request, ParamFetcher $pf): Response
    {
      $entityManager = $this->getDoctrine()->getManager();
      if ($this->getUser()->hasPermit($entityManager->getRepository(Permiso::class)->findOneBy(['nombre' => 'atencion_new']))){
        $ins= new Institucion();
        $ins->setNombre($pf->get('nombre'));
        $ins->setDirector($pf->get('director'));
        $ins->setTelefono($pf->get('telefono'));
        $ins->setRegionSanitariaId($pf->get('regionSanitariaId'));
        $tipo=$entityManager->getRepository(TipoInstitucion::class)->findOneBy(['id' => $pf->get('tipoInstitucionId')]);
        $ins->setTipoInstitucion($tipo);
        $ins->setCoordenadas($pf->get('coordenadas'));
        $entityManager->persist($ins);
        $entityManager->flush();
        return new Response('Paciente agregado', 200);
        }
       else {
        return new Response("No tienes permiso para realizar esa acción", 400);
      }
    }

    /**
     * @Route("/{id}/edit", name="institucion_edit", methods={"POST"})
     * @SWG\Response(response=200, description="Institution was edit successfully")
     * @SWG\Tag(name="Institution")
     * @RequestParam(name="nombre", strict=true, nullable=false, allowBlank=false, description="Nombre.")
     * @RequestParam(name="director", strict=true, nullable=false, allowBlank=false, description="Director.")
     * @RequestParam(name="telefono", strict=true, nullable=false, allowBlank=false, description="Teléfono.")
     * @RequestParam(name="tipoInstitucionId",strict=true, nullable=false, allowBlank=false, description="Tipo de institucion.")
     * @RequestParam(name="regionSanitariaId",strict=true, nullable=false, allowBlank=false, description="Region Sanitaria.")
     * @RequestParam(name="coordenadas", strict=true, nullable=false, allowBlank=false, description="Coordenadas.")
     * 
     * @param ParamFetcher $pf
     */
    public function edit(Request $request, ParamFetcher $pf, $id): Response
    {
      $entityManager = $this->getDoctrine()->getManager();
      if ($this->getUser()->hasPermit($entityManager->getRepository(Permiso::class)->findOneBy(['nombre' => 'atencion_update']))){
        $ins= $entityManager->getRepository(Institucion::class)->findOneBy(['id'=> $id]);
        $ins->setNombre($pf->get('nombre'));
        $ins->setDirector($pf->get('director'));
        $ins->setTelefono($pf->get('telefono'));
        $ins->setRegionSanitariaId($pf->get('regionSanitariaId'));
        $tipo=$entityManager->getRepository(TipoInstitucion::class)->findOneBy(['id' => $pf->get('tipoInstitucionId')]);
        $ins->setTipoInstitucion($tipo);
        $ins->setCoordenadas($pf->get('coordenadas'));
        $entityManager->persist($ins);
        $entityManager->flush();
        return new Response('Paciente agregado', 200);
        }
       else {
        return new Response("No tienes permiso para realizar esa acción", 400);
      }
    }
    
    /**
     * @Route("/tipos", name="institucion_tipos", methods={"GET"})
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="Institucion")
     */
    public function tiposInstitucion()
    {
      $entityManager = $this->getDoctrine()->getManager();
      $serializer = $this->get('jms_serializer');
      $tipos = $this->getDoctrine()->getRepository(TipoInstitucion::class)->findAll();
      return new Response($serializer->serialize($tipos, "json"), 200);
    }

     /**
     *@Route("/{id}", name="institucion_delete", methods={"DELETE"})
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="Institucion")
     */
    public function delete(Request $request, Institucion $institucion): Response
    {
      $entityManager = $this->getDoctrine()->getManager();
      if ($this->getUser()->hasPermit($entityManager->getRepository(Permiso::class)->findOneBy(['nombre' => 'atencion_destroy']))) {
          $entityManager->remove($institucion);
          $entityManager->flush();
        }
      else {
        return new Response("No tienes permiso para realizar esa acción", 400);
      }
      return new Response('Institucion eliminada', 200);
    }

}
