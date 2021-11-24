<?php

namespace App\Controller;

use App\Entity\NavbarSubMenu;
use App\Form\NavbarSubMenuType;
use App\Repository\NavbarSubMenuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/navbarsub")
 */
class NavbarSubMenuController extends AbstractController
{
    /**
     * @Route("/", name="navbar_sub_menu_index", methods={"GET"})
     */
    public function index(NavbarSubMenuRepository $navbarSubMenuRepository): Response
    {
        return $this->render('navbar_sub_menu/index.html.twig', [
            'navbar_sub_menus' => $navbarSubMenuRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="navbar_sub_menu_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $navbarSubMenu = new NavbarSubMenu();
        $form = $this->createForm(NavbarSubMenuType::class, $navbarSubMenu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($navbarSubMenu);
            $entityManager->flush();

            return $this->redirectToRoute('navbar_sub_menu_index');
        }

        return $this->render('navbar_sub_menu/new.html.twig', [
            'navbar_sub_menu' => $navbarSubMenu,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="navbar_sub_menu_show", methods={"GET"})
     */
    public function show(NavbarSubMenu $navbarSubMenu): Response
    {
        return $this->render('navbar_sub_menu/show.html.twig', [
            'navbar_sub_menu' => $navbarSubMenu,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="navbar_sub_menu_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, NavbarSubMenu $navbarSubMenu): Response
    {
        $form = $this->createForm(NavbarSubMenuType::class, $navbarSubMenu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('navbar_sub_menu_index');
        }

        return $this->render('navbar_sub_menu/edit.html.twig', [
            'navbar_sub_menu' => $navbarSubMenu,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="navbar_sub_menu_delete", methods={"DELETE"})
     */
    public function delete(Request $request, NavbarSubMenu $navbarSubMenu): Response
    {
        if ($this->isCsrfTokenValid('delete'.$navbarSubMenu->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($navbarSubMenu);
            $entityManager->flush();
        }

        return $this->redirectToRoute('navbar_sub_menu_index');
    }
}
