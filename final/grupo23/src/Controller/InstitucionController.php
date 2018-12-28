<?php

namespace App\Controller;

use App\Entity\Institucion;
use App\Form\InstitucionType;
use App\Repository\InstitucionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/institucion")
 */
class InstitucionController extends AbstractController
{
    /**
     * @Route("/", name="institucion_index", methods={"GET"})
     */
    public function index(InstitucionRepository $institucionRepository): Response
    {
        return $this->render('institucion/index.html.twig', ['institucions' => $institucionRepository->findAll()]);
    }

    /**
     * @Route("/new", name="institucion_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $institucion = new Institucion();
        $form = $this->createForm(InstitucionType::class, $institucion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($institucion);
            $entityManager->flush();

            return $this->redirectToRoute('institucion_index');
        }

        return $this->render('institucion/new.html.twig', [
            'institucion' => $institucion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="institucion_show", methods={"GET"})
     */
    public function show(Institucion $institucion): Response
    {
        return $this->render('institucion/show.html.twig', ['institucion' => $institucion]);
    }

    /**
     * @Route("/{id}/edit", name="institucion_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Institucion $institucion): Response
    {
        $form = $this->createForm(InstitucionType::class, $institucion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('institucion_index', ['id' => $institucion->getId()]);
        }

        return $this->render('institucion/edit.html.twig', [
            'institucion' => $institucion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="institucion_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Institucion $institucion): Response
    {
        if ($this->isCsrfTokenValid('delete'.$institucion->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($institucion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('institucion_index');
    }
}
