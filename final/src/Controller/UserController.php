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
    public function index(Request $request)
    {
        $serializer = $this->get('jms_serializer');
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        foreach($users as $u){
            $this->getPermisos($u);
            }
        return new Response($serializer->serialize($users, "json"));
    }

    /**
     *@Route("/new", name="usuario_new", methods={"POST"})
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="User")
     */
    public function new(Request $request): Response
    {
      $dataJson = $request->getContent();
      $data = json_decode($dataJson, true);
      $user = new User();
      $user->setFirstName($data['firstName']);
      $user->setLastName($data['lastName']);
      $user->setUsername($data['username']);
      $user->setEmail($data['email']);
      $user->setPassword($this->passEncoder->encodePassword($user, $data['newPass']));
      $user->setRoles($data['roles']);
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($user);
      $entityManager->flush();
      return new Response('Usuario agregado');
    }

    /**
     *@Route("/{id}/edit", name="usuario_edit", methods={"POST"})
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="User")
     */
    public function edit(Request $request, User $user): Response
    {
      $dataJson = $request->getContent();
      $data = json_decode($dataJson, true);
      $entityManager = $this->getDoctrine()->getManager();
      $user->setFirstName($data['firstName']);
      $user->setLastName($data['lastName']);
      $user->setUsername($data['username']);
      $user->setEmail($data['email']);
      if ($data['passHasBeenModified']) {
        if ( !empty($data['oldPass']) && !empty($data['newPass']) && !empty($data['repeatNewPass']) ) {
          if ( $data['newPass'] == $data['repeatNewPass'] ) {
            $encoderService = $this->container->get('security.password_encoder');
            if ( $encoderService->isPasswordValid($user, strval($data['oldPass'])) ) {
              $user->setPassword($this->passEncoder->encodePassword($user, strval($data['newPass'])));
            } else {
              throw new \Exception("La contraseña actual ingresada no es correcta", 1);
            }
          } else {
            throw new \Exception("Contraseña y repetir contraseña no coinciden", 1);
          }
        } else {
          throw new \Exception("Faltó completar alguno de los campos de contraseña", 1);
        }
      }
      $user->setRoles($data['roles']);
      $entityManager->persist($user);
      $entityManager->flush();
      return new Response('Usuario editado');
    }

    /**
     *@Route("/{id}", name="usuario_delete", methods={"DELETE"})
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="User")
     */
    public function delete(Request $request, User $usuario): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($usuario);
        $entityManager->flush();
        return new Response('Usuario eliminado');
    }

    /**
     *@Route("/{id}/edit_state", name="usuario_edit_state", methods={"GET", "POST"})
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="User")
     */
    public function editState(Request $request, User $user): Response
      {
        $entityManager = $this->getDoctrine()->getManager();
        $currentState = $user->getActivo();
        $newState = ($currentState == 1 ? 0 : 1);
        $user->setActivo($newState);
        $entityManager->persist($user);
        $entityManager->flush();
        return new Response('okey');
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
