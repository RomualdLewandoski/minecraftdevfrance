<?php

namespace App\Controller;

use App\Entity\Brouillon;
use App\Repository\BrouillonRepository;
use App\Repository\ForumRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BrouillonController extends AbstractController
{
    /**
     * @Route("/brouillon", name="brouillon", methods={"POST"})
     */
    public function index(UserRepository $userRepository, ForumRepository $forumRepository, BrouillonRepository $brouillonRepository, Request $request): JsonResponse
    {
        $obj = new \stdClass();
        $userName = $request->request->get("userName");
        $forumId = $request->request->get("forumId");

        if ($this->getUser()->getUsername() != $userName) {
            $obj->state = 1;
            $obj->err = "Username post & session username missmatch";
        } else {
            $forum = $forumRepository->find($forumId);
            if ($forum == null) {
                $obj->state = 1;
                $obj->err = "Unknown forum";
            } else {
                $user = $userRepository->findOneBy(['username' => $userName]);
                if ($user == null) {
                    $obj->state = 1;
                    $obj->err = "Unknown user";
                } else {
                    $titre = $request->request->get('titre');
                    $message = $request->request->get('message');
                    $flag = false;
                    $brouillon = $brouillonRepository->findOneBy(['author' => $user, 'forum' => $forum]);
                    if ($brouillon == null) {
                        $flag = true;
                        $brouillon = new Brouillon();
                        $brouillon->setAuthor($user);
                        $brouillon->setForum($forum);
                    }
                    if ($titre != null) {
                        $brouillon->setTitre($titre);
                    }
                    if ($message != null) {
                        $brouillon->setMessage($message);
                    }
                    $em = $this->getDoctrine()->getManager();
                    if ($flag) {
                        $em->persist($brouillon);
                    }
                    $em->flush();
                    $obj->state = 0;
                }
            }
        }
        return new JsonResponse($obj);
    }

    /**
     * @Route("/brouillon_delete", name="brouillon_delete", methods={"POST"})
     */
    public function delete(UserRepository $userRepository, ForumRepository $forumRepository, BrouillonRepository $brouillonRepository, Request $request): JsonResponse
    {
        $obj = new \stdClass();
        $userName = $request->request->get("userName");
        $forumId = $request->request->get("forumId");

        if ($this->getUser()->getUsername() != $userName) {
            $obj->state = 1;
            $obj->err = "Username post & session username missmatch";
        } else {
            $forum = $forumRepository->find($forumId);
            if ($forum == null) {
                $obj->state = 1;
                $obj->err = "Unknown forum";
            } else {
                $user = $userRepository->findOneBy(['username' => $userName]);
                if ($user == null) {
                    $obj->state = 1;
                    $obj->err = "Unknown user";
                } else {
                    $brouillon = $brouillonRepository->findOneBy(['author' => $user, 'forum' => $forum]);
                    if ($brouillon != null) {
                        $em = $this->getDoctrine()->getManager();
                        $em->remove($brouillon);
                        $em->flush();
                        $obj->state = 0;
                    } else {
                        $obj->state = 1;
                        $obj->err = "Unknown draft";
                    }
                }
            }
        }

        return new JsonResponse($obj);
    }
}
