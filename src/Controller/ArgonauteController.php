<?php

namespace App\Controller;

use App\Entity\Argonaute;
use App\Form\ArgonauteType;
use App\Repository\ArgonauteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/argonaute")
 */
class ArgonauteController extends AbstractController
{
    /**
     * Ajoute des Argonautes et affiche la liste des argonautes
     * 
     * @Route("/", name="argonaute_index", methods={"GET", "POST"})
     * 
     */
    public function index(ArgonauteRepository $argonauteRepository, Request $request): Response
    {
        $argonaute = new Argonaute();
        $form = $this->createForm(ArgonauteType::class, $argonaute);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($argonaute);
            $entityManager->flush();

            return $this->redirectToRoute('argonaute_index');
        }

        return $this->render('argonaute/index.html.twig', [
            'argonautes' => $argonauteRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="argonaute_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Argonaute $argonaute): Response
    {
        $form = $this->createForm(ArgonauteType::class, $argonaute);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('argonaute_index');
        }

        return $this->render('argonaute/edit.html.twig', [
            'argonaute' => $argonaute,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="argonaute_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Argonaute $argonaute): Response
    {
        if ($this->isCsrfTokenValid('delete'.$argonaute->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($argonaute);
            $entityManager->flush();
        }

        return $this->redirectToRoute('argonaute_index');
    }
}
