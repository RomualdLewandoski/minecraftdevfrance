<?php

namespace App\Controller;

use App\Entity\UserTrophy;
use App\Repository\TrophyRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class TrophyGiverController extends AbstractController
{
    /**
     * @Route("/trophy/giver", name="trophy_giver")
     */
    public function index(TrophyRepository $trophyRepository, UserRepository $userRepository)
    {
        $cred = $this->getUser();
        $obj = new \stdClass();
        if ($cred == null) {
            $obj->state = 0;
            $obj->err = "Unknown user";
        }
        $user = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        if ($user == null) {
            $obj->state = 0;
            $obj->err = "Unknown user";
        } else {
            $em = $this->getDoctrine()->getManager();
            $arr = [];
            $trophies = $trophyRepository->findAll();
            foreach ($trophies as $trophy) {
                $flag = false;
                $date = new \DateTime('now');
                if (!$user->hasTrophy($trophy)) {
                    switch ($trophy->getAction()) {
                        case 0:
                            if ($trophy->getValue() <= count($user->getTopics())) {
                                $flag = true;
                            }
                            break;
                        case 1:
                            if ($trophy->getValue() <= count($user->getReplies())) {
                                $flag = true;
                            }
                            break;
                        case 2:
                            if ($trophy->getValue() <= $user->getTotalLike()) {
                                $flag = true;
                            }
                            break;
                        case 3:
                            if ($trophy->getValue() <= $user->countGivenLikes()) {
                                $flag = true;
                            }
                            break;
                        case 4:
                            if (time() >= $user->getCreatedAt()->getTimestamp() + ($trophy->getValue() * (60 * 60 * 24))) {
                                $flag = true;
                                $date->setTimestamp($user->getCreatedAt()->getTimestamp() + ($trophy->getValue() * (60 * 60 * 24)));
                            }
                            break;
                        case 5:
                            if ($trophy->getValue() <= $user->getSuperLikeCount()){
                                $flag = true;
                            }
                            break;
                        default:
                            //on ne fait rien
                            break;
                    }
                }
                if ($flag) {
                    $temp = new \stdClass();
                    $userTrophy = (new UserTrophy())
                        ->setUser($user)
                        ->setTrophy($trophy)
                        ->setDate($date);
                    $em->persist($userTrophy);
                    $em->flush();
                    $temp->name = $trophy->getName();
                    $temp->color = $trophy->getBgColor();
                    array_push($arr, $temp);
                }
            }
            $obj->state = 1;
            $obj->trophies = $arr;
        }
        dump($obj);
        return new JsonResponse($obj);
    }
}
