<?php

namespace App\Controller;

use App\Entity\Trophy;
use App\Form\TrophyType;
use App\Repository\TrophyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/trophy")
 */
class TrophyController extends AbstractController
{
    /**
     * @Route("/", name="trophy_index", methods={"GET"})
     */
    public function index(TrophyRepository $trophyRepository): Response
    {
        return $this->render('admin/pages/trophy/listTrophy.html.twig', [
            'trophies' => $trophyRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="trophy_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $trophy = new Trophy();
        $form = $this->createForm(TrophyType::class, $trophy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trophy);
            $entityManager->flush();

            return $this->redirectToRoute('trophy_index');
        }

        return $this->render('admin/pages/trophy/newTrophy.html.twig', [
            'trophy' => $trophy,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="trophy_show", methods={"GET"})
     */
    public function show(Trophy $trophy): Response
    {
        return $this->render('admin/pages/trophy/viewTrophy.html.twig', [
            'trophy' => $trophy,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="trophy_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Trophy $trophy): Response
    {
        $form = $this->createForm(TrophyType::class, $trophy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('trophy_index');
        }

        return $this->render('admin/pages/trophy/editTrophy.html.twig', [
            'trophy' => $trophy,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="trophy_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Trophy $trophy): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trophy->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($trophy);
            $entityManager->flush();
        }

        return $this->redirectToRoute('trophy_index');
    }
}
