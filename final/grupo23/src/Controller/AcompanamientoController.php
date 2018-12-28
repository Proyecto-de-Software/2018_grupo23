<?php

namespace App\Controller;

use App\Entity\Acompanamiento;
use App\Form\AcompanamientoType;
use App\Repository\AcompanamientoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/acompanamiento")
 */
class AcompanamientoController extends AbstractController
{
    /**
     * @Route("/", name="acompanamiento_index", methods={"GET"})
     */
    public function index(AcompanamientoRepository $acompanamientoRepository): Response
    {
        return $this->render('acompanamiento/index.html.twig', ['acompanamientos' => $acompanamientoRepository->findAll()]);
    }

    /**
     * @Route("/new", name="acompanamiento_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $acompanamiento = new Acompanamiento();
        $form = $this->createForm(AcompanamientoType::class, $acompanamiento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($acompanamiento);
            $entityManager->flush();

            return $this->redirectToRoute('acompanamiento_index');
        }

        return $this->render('acompanamiento/new.html.twig', [
            'acompanamiento' => $acompanamiento,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="acompanamiento_show", methods={"GET"})
     */
    public function show(Acompanamiento $acompanamiento): Response
    {
        return $this->render('acompanamiento/show.html.twig', ['acompanamiento' => $acompanamiento]);
    }

    /**
     * @Route("/{id}/edit", name="acompanamiento_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Acompanamiento $acompanamiento): Response
    {
        $form = $this->createForm(AcompanamientoType::class, $acompanamiento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('acompanamiento_index', ['id' => $acompanamiento->getId()]);
        }

        return $this->render('acompanamiento/edit.html.twig', [
            'acompanamiento' => $acompanamiento,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="acompanamiento_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Acompanamiento $acompanamiento): Response
    {
        if ($this->isCsrfTokenValid('delete'.$acompanamiento->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($acompanamiento);
            $entityManager->flush();
        }

        return $this->redirectToRoute('acompanamiento_index');
    }
}
