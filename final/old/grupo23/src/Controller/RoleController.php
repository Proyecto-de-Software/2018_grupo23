<?php

namespace App\Controller;

use App\Entity\Role;
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

/**
 * @Route("/role")
 */
class RoleController extends FOSRestController
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
     * @Route("/", name="role_index", methods={"GET"})
     */
    public function index(): JsonResponse
    {
      $roles = $this->getDoctrine()->getRepository(Role::class)->findAll();
      $data = $this->serializer->serialize($roles, 'json',[
              'circular_reference_handler' => function ($object) { //Meneja la busqueda ciclica por la bd
                  return $object->getId();
              },
              'ignored_attributes' => array('rol','permiso') // Evita que se loopeen los datos entre las entidades
          ]);
      return new JsonResponse($data);
    }

}
