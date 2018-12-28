<?php

namespace App\Controller;

use App\Entity\MotivoConsulta;
use App\Form\MotivoConsultaType;
use App\Repository\MotivoConsultaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/motivo/consulta")
 */
class MotivoConsultaController extends AbstractController
{
    /**
     * @Route("/", name="motivo_consulta_index", methods={"GET"})
     */
    public function index(MotivoConsultaRepository $motivoConsultaRepository): Response
    {
        return $this->render('motivo_consulta/index.html.twig', ['motivo_consultas' => $motivoConsultaRepository->findAll()]);
    }

    /**
     * @Route("/new", name="motivo_consulta_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $motivoConsultum = new MotivoConsulta();
        $form = $this->createForm(MotivoConsultaType::class, $motivoConsultum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($motivoConsultum);
            $entityManager->flush();

            return $this->redirectToRoute('motivo_consulta_index');
        }

        return $this->render('motivo_consulta/new.html.twig', [
            'motivo_consultum' => $motivoConsultum,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="motivo_consulta_show", methods={"GET"})
     */
    public function show(MotivoConsulta $motivoConsultum): Response
    {
        return $this->render('motivo_consulta/show.html.twig', ['motivo_consultum' => $motivoConsultum]);
    }

    /**
     * @Route("/{id}/edit", name="motivo_consulta_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, MotivoConsulta $motivoConsultum): Response
    {
        $form = $this->createForm(MotivoConsultaType::class, $motivoConsultum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('motivo_consulta_index', ['id' => $motivoConsultum->getId()]);
        }

        return $this->render('motivo_consulta/edit.html.twig', [
            'motivo_consultum' => $motivoConsultum,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="motivo_consulta_delete", methods={"DELETE"})
     */
    public function delete(Request $request, MotivoConsulta $motivoConsultum): Response
    {
        if ($this->isCsrfTokenValid('delete'.$motivoConsultum->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($motivoConsultum);
            $entityManager->flush();
        }

        return $this->redirectToRoute('motivo_consulta_index');
    }
}
