<?php

namespace App\Controller;

use App\Entity\Configuracion;
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
 * @Route("/configuracion")
 */
class ConfiguracionController extends FOSRestController
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
     * @Route("/", name="configuracion_index", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        $config = $this->getDoctrine()->getRepository(Configuracion::class)->findAll();
        $response = $this->serializer->serialize($config, 'json');
        return new JsonResponse($response);
    }

    /**
     * @Route("/new", name="configuracion_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $data = $request->getContent();
        // $titulo = $request->request->get('titulo');
        // $email = $request->request->get('email');
        // $descripcion = $request->request->get('descripcion');
        // $estado = $request->request->get('estado');
        // $paginado = $request->request->get('paginado');
        // $columna_uno = $request->request->get('columna_uno');
        // $columna_dos = $request->request->get('columna_dos');
        // $columna_tres = $request->request->get('columna_tres');
        // $titulo_col_uno = $request->request->get('titulo_col_uno');
        // $titulo_col_dos = $request->request->get('titulo_col_dos');
        // $titulo_col_tres = $request->request->get('titulo_col_tres');
        // $config = new Configuracion();
        // $entityManager = $this->getDoctrine()->getManager();
        // $entityManager->persist($configuracion);
        // $entityManager->flush();
        return new Response($data);
    }

    /**
     * @Route("/{id}", name="configuracion_show", methods={"GET"})
     */
    public function show(Configuracion $configuracion): Response
    {
        return $this->render('configuracion/show.html.twig', [
            'configuracion' => $configuracion,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="configuracion_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Configuracion $configuracion): Response
    {
        $form = $this->createForm(Configuracion1Type::class, $configuracion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('configuracion_index', [
                'id' => $configuracion->getId(),
            ]);
        }

        return $this->render('configuracion/edit.html.twig', [
            'configuracion' => $configuracion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="configuracion_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Configuracion $configuracion): Response
    {
        if ($this->isCsrfTokenValid('delete'.$configuracion->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($configuracion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('configuracion_index');
    }
}
