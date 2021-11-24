<?php

namespace App\Controller;

use App\Entity\CatForum;
use App\Form\CatForumType;
use App\Repository\CatForumRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/cat/forum")
 */
class CatForumController extends AbstractController
{
    /**
     * @Route("/", name="cat_forum_index", methods={"GET"})
     */
    public function index(CatForumRepository $catForumRepository): Response
    {
        return $this->render('admin/pages/catForum/listCatForum.html.twig', [
            'cat_forums' => $catForumRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="cat_forum_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $catForum = new CatForum();
        $form = $this->createForm(CatForumType::class, $catForum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($catForum);
            $entityManager->flush();

            return $this->redirectToRoute('cat_forum_index');
        }

        return $this->render('admin/pages/catForum/newCatForum.html.twig', [
            'cat_forum' => $catForum,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cat_forum_show", methods={"GET"})
     */
    public function show(CatForum $catForum): Response
    {
        return $this->render('admin/pages/catForum/viewCatForum.html.twig', [
            'cat_forum' => $catForum,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="cat_forum_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CatForum $catForum): Response
    {
        $form = $this->createForm(CatForumType::class, $catForum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cat_forum_index');
        }

        return $this->render('admin/pages/catForum/editCatForum.html.twig', [
            'cat_forum' => $catForum,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cat_forum_delete", methods={"DELETE"})
     */
    public function delete(Request $request, CatForum $catForum): Response
    {
        if ($this->isCsrfTokenValid('delete' . $catForum->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            foreach ($catForum->getForums() as $forum) {
                foreach ($forum->getTopics() as $topic) {
                    foreach ($topic->getReplies() as $reply) {
                        if ($topic->getSolution() == $reply) {
                            $topic->setSolution(null);
                            $entityManager->flush();
                        }
                        $entityManager->remove($reply);
                    }
                    $entityManager->remove($topic);
                }
                $entityManager->remove($forum);
            }
            $entityManager->remove($catForum);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cat_forum_index');
    }
}
