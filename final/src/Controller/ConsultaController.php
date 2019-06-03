<?php

namespace App\Controller;

use App\Entity\Consulta;
use App\Entity\Paciente;
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
    public function index(Request $request, Paciente $paciente) : response
    {
        $serializer = $this->get('jms_serializer');
        if ($this->getUser()->hasPermit($entityManager->getRepository(Permiso::class)->findOneBy(['nombre' => 'atencion_show']))) {
          $querys = $this->getDoctrine()->getRepository(Consulta::class)->findBy(['paciente_id' => $paciente.id]);
          return new Response($serializer->serialize($querys, "json"));
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

}
