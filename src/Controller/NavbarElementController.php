<?php

namespace App\Controller;

use App\Entity\NavbarMenu;
use App\Form\NavbarMenuType;
use App\Repository\NavbarElementRepository;
use App\Repository\NavbarMenuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/navbar")
 */
class NavbarElementController extends AbstractController
{
    /**
     * @Route("/", name="navbar_element_index", methods={"GET"})
     */
    public function index(NavbarMenuRepository $navbarElementRepository): Response
    {
        return $this->render('admin/pages/navbar_element/listNavbar.html.twig', [
            'navbar_elements' => $navbarElementRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="navbar_element_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $navbarElement = new NavbarMenu();
        $form = $this->createForm(NavbarMenuType::class, $navbarElement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($navbarElement);
            $entityManager->flush();

            return $this->redirectToRoute('navbar_element_index');
        }

        return $this->render('admin/pages/navbar_element/newNavbar.html.twig', [
            'navbar_element' => $navbarElement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="navbar_element_show", methods={"GET"})
     */
    public function show(NavbarMenu $navbarElement): Response
    {
        return $this->render('admin/pages/navbar_element/viewNavbar.html.twig', [
            'navbar_element' => $navbarElement,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="navbar_element_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, NavbarMenu $navbarElement): Response
    {
        $form = $this->createForm(NavbarMenuType::class, $navbarElement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('navbar_element_index');
        }

        return $this->render('admin/pages/navbar_element/editNavbar.html.twig', [
            'navbar_element' => $navbarElement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="navbar_element_delete", methods={"DELETE"})
     */
    public function delete(Request $request, NavbarMenu $navbarElement): Response
    {
        if ($this->isCsrfTokenValid('delete'.$navbarElement->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($navbarElement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('navbar_element_index');
    }
}
