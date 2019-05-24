<?php

namespace App\Controller;

use App\Entity\Consulta;
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
use Swagger\Annotations as SWG;


/**
* Class ConsultaController
*
* @Route("/consulta")
*/

class ConsultaController extends AbstractController
{

    public function index()
    {
        return $this->render('consulta/index.html.twig', [
            'controller_name' => 'ConsultaController',
        ]);
    }


    /**
     *@Route("/consulta/reportes/motivo", name="reporte_consulta_motivo", methods={"GET"})
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="Consulta")
     */
    public function viewAttentionsByReason(): JsonResponse
    {
      $attentions = $this->getDoctrine()->getRepository(Consulta::class)->findByReason();
      $response = $this->serializer->serialize($attentions, 'json');
      return new JsonResponse($response);
    }

    /**
     *@Route("/consulta/reportes/genero", name="reporte_consulta_genero", methods={"GET"})
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="Consulta")
     */
    public function viewAttentionsByGenre(): JsonResponse
    {
      $attentions = $this->getDoctrine()->getRepository(Consulta::class)->findByGenre();
      $response = $this->serializer->serialize($attentions, 'json');
      return new JsonResponse($response);
    }

    /**
     *@Route("/consulta/reportes/localidad", name="reporte_consulta_localidad", methods={"GET"})
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="Consulta")
     */
    public function viewAttentionsByLocation(): JsonResponse
    {
      $attentions = $this->getDoctrine()->getRepository(Consulta::class)->findByLocation();
      $response = $this->serializer->serialize($attentions, 'json');
      return new JsonResponse($response);
    }

}
