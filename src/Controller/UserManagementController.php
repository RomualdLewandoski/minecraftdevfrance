<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRankSetterType;
use App\Repository\UserRankRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user")
 */
class UserManagementController extends AbstractController
{
    /**
     * @Route("/", name="user_management")
     */
    public function index(UserRepository $userRepository)
    {
        return $this->render('admin/pages/user/listUser.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="user_management_edit")
     */
    public function editUser(Request $request, User $user)
    {

        $form = $this->createForm(UserRankSetterType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $rank = $form->get("UserRanks")->getData();
            $user->addUserRank($rank);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_management_edit', ['id' => $user->getId()]);
        }

        return $this->render('admin/pages/user/editUser.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/rank/delete/{idUser}/{idRank}", name="user_management_remove_rank")
     */
    public function removeRank(UserRepository $userRepository, UserRankRepository $userRankRepository, int $idUser, int $idRank)
    {
        $user = $userRepository->find($idUser);
        $rank = $userRankRepository->find($idRank);
        if ($user == null OR $rank == null){
            return $this->redirectToRoute('user_management');
        }else{
            $user->removeUserRank($rank);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('user_management_edit', ['id' => $user->getId()]);
        }
    }
}
