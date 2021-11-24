<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BanController extends AbstractController
{
    /**
     * @Route("/ban", name="ban" , methods={"POST"})
     */
    public function index(Request $request, UserRepository $userRepository): JsonResponse
    {
        $obj = new \stdClass();
        $uName = $request->request->get('userName');
        if ($uName == null) {
            $obj->state = 1;
        } else {
            $user = $userRepository->findOneBy(['username' => $uName]);
            if ($user == null) {
                $obj->state = 1;
                //do nothing
            } else {
                if ($user->getIsBanned()) {
                    $release = $user->getReleasedAt()->getTimestamp();
                    $time = time();
                    if ($time > $release ){
                        $user->setIsBanned(false);
                        $em = $this->getDoctrine()->getManager();
                        $em->flush();
                        $obj->state = 0;
                        //do refresh
                    }else{
                        $obj->state = 3;
                        //do nothing
                    }
                } else {
                    $obj->state = 2;
                    //do nothing
                }
            }

        }

        return new JsonResponse($obj);
    }
}
