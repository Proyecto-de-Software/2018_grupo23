<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Entity\Role;
use App\Form\UsuarioType;
use App\Repository\UsuarioRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Route("/usuario")
 */
class UsuarioController extends FOSRestController
{

    /** @var SerializerInterface */
    private $serializer;

    /** @var UserPasswordEncoderInterface */
    private $passEncoder;

    /**
     * @param SerializerInterface $serializer
     * @param UserPasswordEncoderInterface $passEncoder
     */
    public function __construct(SerializerInterface $serializer, UserPasswordEncoderInterface $passEncoder)
    {
        $this->serializer = $serializer;
        $this->passEncoder = $passEncoder;
    }

    /**
     * @Route("/", name="usuario_index", methods={"GET"})
     */
    public function index(): JsonResponse
    {
      $users = $this->getDoctrine()->getRepository(Usuario::class)->findAll();
      $data = $this->serializer->serialize($users, 'json',[
              'circular_reference_handler' => function ($object) { //Meneja la busqueda ciclica por la bd
                  return $object->getId();
              },
              'ignored_attributes' => array('usuario','rol') // Evita que se loopeen los datos entre las entidades
          ]);
      return new JsonResponse($data);
    }

    /**
     * @Route("/new", name="usuario_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
      $dataJson = $request->getContent();
      $data = json_decode($dataJson, true);
      $user = new Usuario();
      $user->setFirstName(strval($data['firstName']));
      $user->setLastName(strval($data['lastName']));
      $user->setUsername(strval($data['username']));
      $user->setEmail(strval($data['email']));
      $user->setPassword($this->passEncoder->encodePassword($user, strval($data['newPass'])));
      // $user_roles = array();
      // foreach ((array)$data['roles'] as $roleString) {
      //   $role = $entityManager->getRepository(Role::Class)->findBy(array('nombre' => $roleString));
      //   array_push($user_roles, $role);
      // };
      // $user->setRoles($user_roles);
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($user);
      $entityManager->flush();
      return new Response('Usuario agregado');
    }

    /**
     * @Route("/{id}", name="usuario_show", methods={"GET"})
     */
    public function show(Usuario $usuario): JsonResponse
    {
        //$kawai = $this->serializer->serialize($usuario, 'json',[
        //    'circular_reference_handler' => function ($object) {
        //        return $object->getId();
        //    }
        //]);
        //$entityManager = $this->getDoctrine()->getManager();
        //$usuario = $entityManager->getRepository("App\Entity\Usuario")->findBy(array("id"=>1));

        //$normalizer = new ObjectNormalizer();
        //$normalizer->setIgnoredAttributes(array('roles','usuario','permiso'));
        //$encoder = new JsonEncoder();
        //$serializer = new Serializer(array($normalizer),array($encoder));

        $data = $this->serializer->serialize($usuario, 'json',[
                'circular_reference_handler' => function ($object) { //Meneja la busqueda ciclica por la bd
                    return $object->getId();
                },
                'ignored_attributes' => array('usuario','rol') // Evita que se loopeen los datos entre las entidades
            ]);
        return new JsonResponse($data);
    }

    /**
     * @Route("/{id}/edit", name="usuario_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Usuario $user): JsonResponse
    {
      $dataJson = $request->getContent();
      $data = json_decode($dataJson, true);
      $entityManager = $this->getDoctrine()->getManager();
      $user->setFirstName(strval($data['firstName']));
      $user->setLastName(strval($data['lastName']));
      $user->setUsername(strval($data['username']));
      $user->setEmail(strval($data['email']));
      if (strval($data['passHasBeenModified'])) {
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
      // $user_roles = array();
      // foreach ((array)$data['roles'] as $roleString) {
      //   $role = $entityManager->getRepository(Role::Class)->findBy(array('nombre' => $roleString));
      //   array_push($user_roles, $role);
      // };
      // $user->setRoles($user_roles);
      $entityManager->persist($user);
      $entityManager->flush();
      return new JsonResponse($user);
    }

    /**
     * @Route("/{id}", name="usuario_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Usuario $usuario): JsonResponse
    {
      if ( true ) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($usuario);
        $entityManager->flush();
        return new JsonResponse(array("msg" => 'Usuario eliminado'));
      }else {
        return new JsonResponse(array("msg" => 'El usuario no pudo ser eliminado'));
      }
    }

    /**
     * @Route("/{id}/edit_state", name="usuario_edit_state", methods={"GET","POST"})
     */
    public function editState(Request $request, Usuario $user): Response
      {
        $entityManager = $this->getDoctrine()->getManager();
        $currentState = $user->getActivo();
        $newState = ($currentState == 1 ? 0 : 1);
        $user->setActivo($newState);
        $entityManager->persist($user);
        $entityManager->flush();
        return new Response('okey');
      }

}
