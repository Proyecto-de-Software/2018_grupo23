<?php

namespace App\Controller;

use App\Entity\User;
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
 * Class UserController
 *
 * @Route("/user")
 */
class UserController extends FOSRestController
{
    
    /**
     *@Route("/index", name="usuario_index", methods={"GET"})
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="User")
     */
    public function index(Request $request) {

        $serializer = $this->get('jms_serializer');
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        
        foreach($users as $u){
            $this->getPermisos($u);

            }
 
        return new Response($serializer->serialize($users, "json"));
    }

    //agrega los permisos a cada rol del usuario
    protected function getPermisos($u)
    {
        $emr = $this->getDoctrine()->getRepository(RolesDelSistema::class);
        $ro = $u->getRoles();
        $roles_con_permisos = Array();
        foreach($ro as $u_r){
            $r = $emr->findOneBy(
                ['nombre' => $u_r]
            );
            array_push($roles_con_permisos,$r);
        }
        $u->setRoles($roles_con_permisos);
    }


}