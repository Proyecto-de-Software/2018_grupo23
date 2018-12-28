<?php

namespace App\Controller;

use App\Entity\TratamientoFarmacologico;
use App\Form\TratamientoFarmacologicoType;
use App\Repository\TratamientoFarmacologicoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tratamiento/farmacologico")
 */
class TratamientoFarmacologicoController extends AbstractController
{
    /**
     * @Route("/", name="tratamiento_farmacologico_index", methods={"GET"})
     */
    public function index(TratamientoFarmacologicoRepository $tratamientoFarmacologicoRepository): Response
    {
        return $this->render('tratamiento_farmacologico/index.html.twig', ['tratamiento_farmacologicos' => $tratamientoFarmacologicoRepository->findAll()]);
    }

    /**
     * @Route("/new", name="tratamiento_farmacologico_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $tratamientoFarmacologico = new TratamientoFarmacologico();
        $form = $this->createForm(TratamientoFarmacologicoType::class, $tratamientoFarmacologico);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tratamientoFarmacologico);
            $entityManager->flush();

            return $this->redirectToRoute('tratamiento_farmacologico_index');
        }

        return $this->render('tratamiento_farmacologico/new.html.twig', [
            'tratamiento_farmacologico' => $tratamientoFarmacologico,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tratamiento_farmacologico_show", methods={"GET"})
     */
    public function show(TratamientoFarmacologico $tratamientoFarmacologico): Response
    {
        return $this->render('tratamiento_farmacologico/show.html.twig', ['tratamiento_farmacologico' => $tratamientoFarmacologico]);
    }

    /**
     * @Route("/{id}/edit", name="tratamiento_farmacologico_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TratamientoFarmacologico $tratamientoFarmacologico): Response
    {
        $form = $this->createForm(TratamientoFarmacologicoType::class, $tratamientoFarmacologico);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tratamiento_farmacologico_index', ['id' => $tratamientoFarmacologico->getId()]);
        }

        return $this->render('tratamiento_farmacologico/edit.html.twig', [
            'tratamiento_farmacologico' => $tratamientoFarmacologico,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tratamiento_farmacologico_delete", methods={"DELETE"})
     */
    public function delete(Request $request, TratamientoFarmacologico $tratamientoFarmacologico): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tratamientoFarmacologico->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tratamientoFarmacologico);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tratamiento_farmacologico_index');
    }
}
