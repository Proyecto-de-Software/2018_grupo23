<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Permiso;
use App\Entity\RolesDelSistema;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Request\ParamFetcher;
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

    /** @var UserPasswordEncoderInterface */
    private $passEncoder;

    /**
     * @param UserPasswordEncoderInterface $passEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passEncoder)
    {
        $this->passEncoder = $passEncoder;
    }

    /**
     *@Route("/index", name="usuario_index", methods={"GET"})
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="User")
     */
    public function index(Request $request): Response
    {
      $entityManager = $this->getDoctrine()->getManager();
      if ($this->getUser()->hasPermit($entityManager->getRepository(Permiso::class)->findOneBy(['nombre' => 'usuario_index']))) {
        $serializer = $this->get('jms_serializer');
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        foreach($users as $u){
            $this->getPermisos($u);
        }
        return new Response($serializer->serialize($users, "json"), 200);
      } else {
        return new Response("No tienes permiso para realizar esa acción", 400);
      }
    }

    /**
     * @Route("/new", name="usuario_new", methods={"POST"})
     * @SWG\Response(response=200, description="User was created successfully")
     * @SWG\Tag(name="User")
     * @RequestParam(name="firstName", strict=true, nullable=false, allowBlank=false, description="Nombre.")
     * @RequestParam(name="lastName", strict=true, nullable=false, allowBlank=false, description="Apellido.")
     * @RequestParam(name="username", strict=true, nullable=false, allowBlank=false, description="Nombre de usuario.")
     * @RequestParam(name="email", strict=true, nullable=false, allowBlank=false, description="Email.")
     * @RequestParam(name="roles", description="Roles.")
     * @RequestParam(name="newPass", strict=true, nullable=false, allowBlank=false, description="Password.")
     * @RequestParam(name="repeatNewPass", strict=true, nullable=false, allowBlank=false, description="rePassword.")
     * @param ParamFetcher $pf
     */
    public function new(Request $request, ParamFetcher $pf): Response
    {
      $entityManager = $this->getDoctrine()->getManager();
      if ($this->getUser()->hasPermit($entityManager->getRepository(Permiso::class)->findOneBy(['nombre' => 'usuario_new']))) {
        if ($this->notAlreadyExists('email', $pf->get('email'))) {
          if ($this->notAlreadyExists('username', $pf->get('username'))) {
            $user = new User();
            $user->setFirstName($pf->get('firstName'));
            $user->setLastName($pf->get('lastName'));
            $user->setUsername($pf->get('username'));
            $user->setEmail($pf->get('email'));
            $user->setRoles($pf->get('roles'));
            if ( $pf->get('newPass') == $pf->get('repeatNewPass') ) {
              $user->setPassword($this->passEncoder->encodePassword($user, $pf->get('newPass')));
            } else {
              return new Response("Contraseña y repetir contraseña no coinciden", 403);
            }
            $entityManager->persist($user);
            $entityManager->flush();
            return new Response('Usuario agregado', 200);
          } else {
            return new Response("El nombre de usuario ingresado ya se encuentra en uso", 400);
          }
        } else {
          return new Response("El email ingresado ya se encuentra en uso", 400);
        }
      } else {
        return new Response("No tienes permiso para realizar esa acción", 400);
      }
    }

    /**
     *@Route("/{id}/edit", name="usuario_edit", methods={"POST"})
     * @SWG\Response(response=200, description="User was edited successfully")
     * @RequestParam(name="firstName", strict=true, nullable=false, allowBlank=false, description="Nombre.")
     * @RequestParam(name="lastName", strict=true, nullable=false, allowBlank=false, description="Apellido.")
     * @RequestParam(name="username", strict=true, nullable=false, allowBlank=false, description="Nombre de usuario.")
     * @RequestParam(name="email", strict=true, nullable=false, allowBlank=false, description="Email.")
     * @RequestParam(name="roles", description="Roles.")
     * @RequestParam(name="oldPass", strict=true, nullable=true, allowBlank=true, description="oldPassword.")
     * @RequestParam(name="newPass", strict=true, nullable=true, allowBlank=true, description="newPassword.")
     * @RequestParam(name="repeatNewPass", strict=true, nullable=true, allowBlank=true, description="rePassword.")
     * @RequestParam(name="passHasBeenModified", strict=true, default=false, nullable=false, allowBlank=false, description="passModified.")
     * @param ParamFetcher $pf
     */
    public function edit(Request $request, User $user, ParamFetcher $pf): Response
    {
      $entityManager = $this->getDoctrine()->getManager();
      if ($this->getUser()->hasPermit($entityManager->getRepository(Permiso::class)->findOneBy(['nombre' => 'usuario_update']))) {
        if ($this->notAlreadyExists('email', $pf->get('email'), $user)) {
          if ($this->notAlreadyExists('username', $pf->get('username'), $user)) {
            $serializer = $this->get('jms_serializer');
            if ($pf->get('passHasBeenModified')) {
              if ( !empty($pf->get('oldPass')) && !empty($pf->get('newPass')) && !empty($pf->get('repeatNewPass')) ) {
                if ( $pf->get('newPass') == $pf->get('repeatNewPass') ) {
                  $encoderService = $this->container->get('security.password_encoder');
                  if ( $encoderService->isPasswordValid($user, $pf->get('oldPass')) ) {
                    $user->setPassword($this->passEncoder->encodePassword($user, $pf->get('newPass')));
                  } else {
                    return new Response("La contraseña actual ingresada no es correcta", 400);
                  }
                } else {
                  return new Response("Contraseña y repetir contraseña no coinciden", 400);
                }
              } else {
                return new Response("Faltó completar alguno de los campos de contraseña", 400);
              }
            }
         } else {
            return new Response("El nombre de usuario ingresado ya se encuentra en uso", 400);
         }
        } else {
          return new Response("El email ingresado ya se encuentra en uso", 400);
        }
        $user->setFirstName($pf->get('firstName'));
        $user->setLastName($pf->get('lastName'));
        $user->setUsername($pf->get('username'));
        $user->setEmail($pf->get('email'));
        $user->setRoles($pf->get('roles'));
        $entityManager->persist($user);
        $entityManager->flush();
        return new Response('Usuario editado', 200);
      } else {
        return new Response("No tienes permiso para realizar esa acción", 400);
      }
    }

    /**
     *@Route("/{id}", name="usuario_delete", methods={"DELETE"})
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="User")
     */
    public function delete(Request $request, User $user): Response
    {
      $entityManager = $this->getDoctrine()->getManager();
      if ($this->getUser()->hasPermit($entityManager->getRepository(Permiso::class)->findOneBy(['nombre' => 'usuario_destroy']))) {
        if ( $this->getUser()->getId() != $user->getId() ) { //no es él mismo
          $entityManager->remove($user);
          $entityManager->flush();
        } else {
          return new Response("No puedes eliminar a ese usuario", 400);
        }
      } else {
        return new Response("No tienes permiso para realizar esa acción", 400);
      }
      return new Response('Usuario eliminado', 200);
    }

    /**
     *@Route("/{id}/edit_state", name="usuario_edit_state", methods={"GET", "POST"})
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="User")
     */
    public function editState(Request $request, User $user): Response
    {
      $entityManager = $this->getDoctrine()->getManager();
      if ($this->getUser()->hasPermit($entityManager->getRepository(Permiso::class)->findOneBy(['nombre' => 'usuario_update']))) {
        if ( $this->getUser()->getId() != $user->getId() ) { //no es él mismo
          $currentState = $user->getActivo();
          $newState = ($currentState == 1 ? 0 : 1);
          $user->setActivo($newState);
          $entityManager->persist($user);
          $entityManager->flush();
        } else {
          return new Response("No puedes bloquear a ese usuario", 400);
        }
        $msg = $currentState == 1 ? 'bloqueado' : 'desbloqueado';
        return new Response('Usuario ' . $msg, 200);
      } else {
        return new Response("No tienes permiso para realizar esa acción", 400);
      }
    }

    function notAlreadyExists($field, $value, $user = false) { //si le pasa value user viene del edit
      $entityManager = $this->getDoctrine()->getManager();
      $user_arr = $entityManager->getRepository(User::class)->findOneBy([$field => $value]);
      if ($user_arr) { //alguien lo tiene
        if ($user) { //edit mode
          return $user->getId() == $user_arr->getId() ? true : false; //es el del mismo que estoy editando
        }else { //new
          return false;
        }
      } //nadie lo tiene
      return true;
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
