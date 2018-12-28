<?php

namespace App\Controller;

use App\Entity\TipoInstitucion;
use App\Form\TipoInstitucionType;
use App\Repository\TipoInstitucionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tipo/institucion")
 */
class TipoInstitucionController extends AbstractController
{
    /**
     * @Route("/", name="tipo_institucion_index", methods={"GET"})
     */
    public function index(TipoInstitucionRepository $tipoInstitucionRepository): Response
    {
        return $this->render('tipo_institucion/index.html.twig', ['tipo_institucions' => $tipoInstitucionRepository->findAll()]);
    }

    /**
     * @Route("/new", name="tipo_institucion_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $tipoInstitucion = new TipoInstitucion();
        $form = $this->createForm(TipoInstitucionType::class, $tipoInstitucion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tipoInstitucion);
            $entityManager->flush();

            return $this->redirectToRoute('tipo_institucion_index');
        }

        return $this->render('tipo_institucion/new.html.twig', [
            'tipo_institucion' => $tipoInstitucion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tipo_institucion_show", methods={"GET"})
     */
    public function show(TipoInstitucion $tipoInstitucion): Response
    {
        return $this->render('tipo_institucion/show.html.twig', ['tipo_institucion' => $tipoInstitucion]);
    }

    /**
     * @Route("/{id}/edit", name="tipo_institucion_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TipoInstitucion $tipoInstitucion): Response
    {
        $form = $this->createForm(TipoInstitucionType::class, $tipoInstitucion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tipo_institucion_index', ['id' => $tipoInstitucion->getId()]);
        }

        return $this->render('tipo_institucion/edit.html.twig', [
            'tipo_institucion' => $tipoInstitucion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tipo_institucion_delete", methods={"DELETE"})
     */
    public function delete(Request $request, TipoInstitucion $tipoInstitucion): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tipoInstitucion->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tipoInstitucion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tipo_institucion_index');
    }
}
