<?php

namespace App\Controller;

use App\Entity\Paciente;
use App\Form\PacienteType;
use App\Repository\PacienteRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController;

/**
 * @Route("/paciente")
 */
class PacienteController extends FOSRestController
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
     * @Route("/", name="paciente_index", methods={"GET"})
     */
    public function index() : JsonResponse
    {
        $patients= $this->getDoctrine()->getRepository(Paciente::class)->findAll();
        $data = $this->serializer->serialize($patients, 'json',[
            'circular_reference_handler' => function ($object) { //Meneja la busqueda ciclica por la bd
                return $object->getId();
            }]);
        return new JsonResponse($data);
    }
}
