<?php

namespace App\Controller;

use App\Entity\Institucion;
use App\Entity\TipoInstitucion;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
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
     * @Route("/tipos", name="institucion_tipos", methods={"POST"})
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


}
