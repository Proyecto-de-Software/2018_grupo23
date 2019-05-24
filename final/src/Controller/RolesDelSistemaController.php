<?php

namespace App\Controller;

use App\Entity\Permiso;
use App\Entity\RolesDelSistema;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

/**
 * Class RolesDelSistemaController
 *
 * @Route("/role")
 */
class RolesDelSistemaController extends FOSRestController
{

    /**
     *@Route("/index", name="roles_del_sistema_index", methods={"GET"})
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="RolesDelSistema")
    */
    public function index()
    {
      $serializer = $this->get('jms_serializer');
      $roles = $this->getDoctrine()->getRepository(RolesDelSistema::class)->findAll();
      return new Response($serializer->serialize($roles, "json"));
    }

}
