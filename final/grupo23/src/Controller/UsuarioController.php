<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Form\UsuarioType;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Get;

use Symfony\Component\Serializer\SerializerInterface;


use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * @Route("/usuario")
 */
class UsuarioController extends FOSRestController
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
     * @Route("/", name="usuario_index", methods={"GET"})
     */
    public function index(UsuarioRepository $usuarioRepository): JsonResponse
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
        $usuario = new Usuario();
        $form = $this->createForm(UsuarioType::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($usuario);
            $entityManager->flush();

            return $this->redirectToRoute('usuario_index');
        }

        return $this->render('usuario/new.html.twig', [
            'usuario' => $usuario,
            'form' => $form->createView(),
        ]);
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
    public function edit(Request $request, Usuario $usuario): Response
    {
        $form = $this->createForm(UsuarioType::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('usuario_index', [
                'id' => $usuario->getId(),
            ]);
        }

        return $this->render('usuario/edit.html.twig', [
            'usuario' => $usuario,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="usuario_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Usuario $usuario): Response
    {
        if ($this->isCsrfTokenValid('delete'.$usuario->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($usuario);
            $entityManager->flush();
        }

        return $this->redirectToRoute('usuario_index');
    }
}
