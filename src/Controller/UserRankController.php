<?php

namespace App\Controller;

use App\Entity\UserRank;
use App\Form\UserRankType;
use App\Repository\UserRankRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/rank")
 */
class UserRankController extends AbstractController
{
    /**
     * @Route("/", name="user_rank_index", methods={"GET"})
     */
    public function index(UserRankRepository $userRankRepository): Response
    {
        return $this->render('admin/pages/ranks/listRanks.html.twig', [
            'user_ranks' => $userRankRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_rank_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $userRank = new UserRank();
        $form = $this->createForm(UserRankType::class, $userRank);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userRank);
            $entityManager->flush();

            return $this->redirectToRoute('user_rank_index');
        }

        return $this->render('admin/pages/ranks/newRanks.html.twig', [
            'user_rank' => $userRank,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_rank_show", methods={"GET"})
     */
    public function show(UserRank $userRank): Response
    {
        return $this->render('admin/pages/ranks/viewRanks.html.twig', [
            'user_rank' => $userRank,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_rank_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, UserRank $userRank): Response
    {
        $form = $this->createForm(UserRankType::class, $userRank);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_rank_index');
        }

        return $this->render('admin/pages/ranks/editRanks.html.twig', [
            'user_rank' => $userRank,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_rank_delete", methods={"DELETE"})
     */
    public function delete(Request $request, UserRank $userRank): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userRank->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($userRank);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_rank_index');
    }
}
