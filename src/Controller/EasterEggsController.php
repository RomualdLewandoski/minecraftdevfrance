<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/easter/eggs")
 */
class EasterEggsController extends AbstractController
{
    /**
     * @Route("/konami", name="konami")
     */
    public function index(UserRepository $userRepository)
    {
        $obj = new \stdClass();
        $cred = $this->getUser();
        if ($cred == null) {
            $obj->state = 0;
            $obj->err = "Non logged User";
        } else {
            $user = $userRepository->findOneBy(['username' => $cred->getUsername()]);
            if ($user == null) {
                $obj->state = 0;
                $obj->err = "Non logged User";
            } else {
                if ($user->getIsKonami()) {
                    $obj->state = 0;
                    $obj->err = "Aleready Done";
                } else {
                    $user->setIsKonami(true);
                    $em = $this->getDoctrine()->getManager();
                    $em->flush();
                    $obj->state = 1;
                }
            }
        }
        return new JsonResponse($obj);
    }

    /**
     * @Route("/doom", name="doom")
     */
    public function doomSlayer(UserRepository $userRepository)
    {
        $obj = new \stdClass();
        $cred = $this->getUser();
        if ($cred == null) {
            $obj->state = 0;
            $obj->err = "Non logged User";
        } else {
            $user = $userRepository->findOneBy(['username' => $cred->getUsername()]);
            if ($user == null) {
                $obj->state = 0;
                $obj->err = "Non logged User";
            } else {
                if ($user->getIsDoom()) {
                    $obj->state = 0;
                    $obj->err = "Aleready Done";
                } else {
                    $user->setIsDoom(true);
                    $user->setUseDoomFont(true);
                    $em = $this->getDoctrine()->getManager();
                    $em->flush();
                    $obj->state = 1;
                }
            }
        }
        return new JsonResponse($obj);
    }
}
