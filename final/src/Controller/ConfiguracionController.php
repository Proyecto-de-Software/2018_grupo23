<?php

namespace App\Controller;

use App\Entity\Configuracion;
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
 * @Route("/configuracion")
 */
class ConfiguracionController extends FOSRestController
{
    /**
     * @Route("/", name="configuracion_index", methods={"GET"})
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="Configuracion")
     */
    public function index()
    {
        $serializer = $this->get('jms_serializer');

        $config = $this->getDoctrine()->getRepository(Configuracion::class)->findAll();

        return new Response($serializer->serialize($config, "json"));
    }

    /**
     * @Route("/new", name="configuracion_new", methods={"GET","POST"})
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="Configuracion")
     */
    public function new(Request $request): Response
    {
        $data = $request->getContent();
        $configArray = json_decode($data, true);
        $em = $this->getDoctrine()->getManager();
        foreach ($configArray as $key => $value) {
          $row = $em->getRepository(Configuracion::class)->findOneBy(array('variable' => $key));
          $row->setValor($value);
          $em->persist($row);
        }
        $em->flush();
        return new Response('exito');
    }

}