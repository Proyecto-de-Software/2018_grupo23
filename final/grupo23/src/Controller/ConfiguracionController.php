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
        $configArray = json_decode($data, true);
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $sql = "
                UPDATE configuracion
                   SET valor = CASE variable
                                 WHEN 'titulo' THEN :titulo
                                 WHEN 'email' THEN :email
                                 WHEN 'descripcion' THEN :descripcion
                                 WHEN 'paginado' THEN :paginado
                                 WHEN 'estado' THEN :estado
                                 WHEN 'columna_uno' THEN :columna_uno
                                 WHEN 'columna_dos' THEN :columna_dos
                                 WHEN 'columna_tres' THEN :columna_tres
                                 WHEN 'titulo_col_uno' THEN :titulo_col_uno
                                 WHEN 'titulo_col_dos' THEN :titulo_col_dos
                                 WHEN 'titulo_col_tres' THEN :titulo_col_tres
                               END";
        $query = $conn->prepare($sql);
        $query->execute( ['titulo' => $configArray['titulo'], 'email' => $configArray['email'], 'descripcion' => $configArray['descripcion'],
                          'paginado' => $configArray['paginado'], 'estado' => $configArray['estado'], 'columna_uno' => $configArray['columna_uno'],
                          'columna_dos' => $configArray['columna_dos'], 'columna_tres' => $configArray['columna_tres'], 'titulo_col_uno' => $configArray['titulo_col_uno'],
                          'titulo_col_dos' => $configArray['titulo_col_dos'], 'titulo_col_tres' => $configArray['titulo_col_tres'] ]);
        return new Response("Qué bueno, pude ejecutar la consulta, la concha bien de su madre!");
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
     * @Route("/{id?}/edit", name="configuracion_edit", methods={"GET","POST"})
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
