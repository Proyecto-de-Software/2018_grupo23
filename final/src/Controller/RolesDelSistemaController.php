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

    /**
     *@Route("/permissions_all", name="permissions_all", methods={"GET"})
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="RolesDelSistema")
    */
    public function getAppPermissions()
    {
      $serializer = $this->get('jms_serializer');
      $perms = $this->getDoctrine()->getRepository(Permiso::class)->findAll();
      return new Response($serializer->serialize($perms, "json"));
    }

    /**
     *@Route("/{id}/edit", name="role_edit", methods={"POST"})
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="RolesDelSistema")
     */
     public function edit(Request $request, RolesDelSistema $role): Response
     {
       $entityManager = $this->getDoctrine()->getManager();
       if ($this->getUser()->hasPermit($entityManager->getRepository(Permiso::class)->findOneBy(['nombre' => 'rol_update']))) {
         $data = json_decode($request->getContent(), true);
         foreach ($role->getPermisos() as $permiso) {
           if ( !in_array($permiso->getNombre(), $data) ) {
             $role->removePermiso($permiso);
           }
         }
         foreach ($data as $perm) {
           $role->addPermiso($entityManager->getRepository(Permiso::class)->findOneBy(['nombre' => $perm]));
         }
         $entityManager->persist($role);
         $entityManager->flush();
         return new Response("Rol editado");
       } else {
         throw new \Exception("Usted no tiene permiso para realizar esa acción", 1);
       }
     }

}
