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

class ConsultaController extends AbstractController
{

    /** @var SerializerInterface */
    private $serializer;

    /**
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @Route("/consulta", name="consulta")
     */
    public function index()
    {
        return $this->render('consulta/index.html.twig', [
            'controller_name' => 'ConsultaController',
        ]);
    }

    /**
    * @Route("consulta/reportes/motivo", name="reporte_consulta_motivo", methods={"GET"})
    */
    public function viewAttentionsByReason(): JsonResponse
    {
      $attentions = $this->getDoctrine()->getRepository(Consulta::class)->findByReason();
      $response = $this->serializer->serialize($attentions, 'json');
      return new JsonResponse($response);
    }

    /**
    * @Route("consulta/reportes/genero", name="reporte_consulta_genero", methods={"GET"})
    */
    public function viewAttentionsByGenre(): JsonResponse
    {
      $attentions = $this->getDoctrine()->getRepository(Consulta::class)->findByGenre();
      $response = $this->serializer->serialize($attentions, 'json');
      return new JsonResponse($response);
    }

    /**
    * @Route("consulta/reportes/localidad", name="reporte_consulta_localidad", methods={"GET"})
    */
    public function viewAttentionsByLocation(): JsonResponse
    {
      $attentions = $this->getDoctrine()->getRepository(Consulta::class)->findByLocation();
      $response = $this->serializer->serialize($attentions, 'json');
      return new JsonResponse($response);
    }

    //
    // public function viewAttentionsBy($result){//engloba lo común a AttentionsByReason y ByGenre
    //   $dataPoints=array();
    //   foreach($result as $row){
    //       $porcentaje= ($row->cant * 100 / $row->total_atenciones);
    //       array_push($dataPoints, array('y'=> $porcentaje, 'label'=> $row->nombre, 'cant'=> $row->cant));
    //   }
    //   echo json_encode($dataPoints, JSON_NUMERIC_CHECK);
    // }
    //
    // public function viewAttentionsByReason(){
    //   $repo= new AttentionRepository();
    //   $this->viewAttentionsBy($repo->getAtencionesPorMotivo());
    // }
    //
    // public function viewAttentionsByGenre(){
    //   $repo= new AttentionRepository();
    //   $this->viewAttentionsBy($repo->getAtencionesPorGenero());
    // }
    //
    // public function viewAttentionsByLocation(){//utiliza la Api de localidades
    //   $repo= new AttentionRepository();
    //   $result= $repo->getAtencionesPorLocalidad();
    //   $localidades=json_decode(@file_get_contents('https://api-referencias.proyecto2018.linti.unlp.edu.ar/localidad'), true);
    //   $loc_array=array();
    //   foreach ($localidades as $key => $value) { //creo un array que me sirve para pasarle el nombre a los dataPoints
    //     $loc_array[$value['id']] = $value['nombre'];
    //   }
    //   $dataPoints=array();
    //   foreach($result as $row){
    //     $porcentaje= ($row->cant * 100 / $row->total_atenciones);
    //     $loc_nombre= $row->id == 0 ? 'Sin localidad asignada' : $loc_array[$row->id];
    //     array_push($dataPoints, array('y'=> $porcentaje, 'label'=> $loc_nombre, 'cant'=> $row->cant));
    //   }
    //   echo json_encode($dataPoints, JSON_NUMERIC_CHECK);
    // }


}
