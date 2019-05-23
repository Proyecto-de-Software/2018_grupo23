<?php

namespace App\Controller;

use App\Entity\Genero;
use App\Form\Genero1Type;
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
 * @Route("/genero")
 */
class GeneroController extends FOSRestController
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
     * @Route("/", name="genero_index", methods={"GET"})
     */
    public function index(): Response
    {
        $generos = $this->getDoctrine()
            ->getRepository(Genero::class)
            ->findAll();

        return $this->render('genero/index.html.twig', ['generos' => $generos]);
    }

    /**
     * @Route("/new", name="genero_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $genero = new Genero();
        $form = $this->createForm(Genero1Type::class, $genero);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($genero);
            $entityManager->flush();

            return $this->redirectToRoute('genero_index');
        }

        return $this->render('genero/new.html.twig', [
            'genero' => $genero,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Get("/{genero}")
     */
    public function show(Genero $genero): JsonResponse
    {
        //return $this->render('genero/show.html.twig', ['genero' => $genero]);
        $kawai = $this->serializer->serialize($genero, 'json');
        return new JsonResponse($kawai);
    }

    /**
     * @Route("/{id}/edit", name="genero_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Genero $genero): Response
    {
        $form = $this->createForm(Genero1Type::class, $genero);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('genero_index', ['id' => $genero->getId()]);
        }

        return $this->render('genero/edit.html.twig', [
            'genero' => $genero,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="genero_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Genero $genero): Response
    {
        if ($this->isCsrfTokenValid('delete'.$genero->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($genero);
            $entityManager->flush();
        }

        return $this->redirectToRoute('genero_index');
    }
}
