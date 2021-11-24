<?php

namespace App\Controller;

use App\Entity\Cookie;
use App\Repository\UserRepository;
use phpDocumentor\Reflection\DocBlock;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProfileCookieController extends AbstractController
{
    /**
     * @Route("/profile/cookie", name="profile_cookie")
     */
    public function index()
    {
        return $this->render('profile_cookie/index.html.twig', [
            'controller_name' => 'ProfileCookieController',
        ]);
    }

    /**
     * @Route("/profile/cookie/api/get" , name="get_cookie", methods={"POST"})
     */
    public function getCookie(UserRepository $userRepository)
    {
        $user = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        if ($user->getCookies() == null) {
            $cookies = (new Cookie())
                ->setUser($user)
                ->setCookies(0)
                ->setFiledLevel(1)
                ->setVillagerLevel(0)
                ->setGolemLevel(0)
                ->setGolemBoost(300);
            $em = $this->getDoctrine()->getManager();
            $em->persist($cookies);
            $em->flush();
        } else {
            $cookies = $user->getCookies()[0];
        }
        $obj = new \stdClass();
        $obj->cookies = $cookies->getCookies();
        $obj->fields = $cookies->getFiledLevel();
        $obj->villager = $cookies->getVillagerLevel();
        $obj->golemLevel = $cookies->getGolemLevel();
        $obj->golemBoost = $cookies->getGolemBoost();
        return new JsonResponse($obj);
    }

    /**
     * @Route("/profile/cookie/api/save", name="save_cookie", methods={"POST"})
     */
    public function saveCookie(Request $request, UserRepository $userRepository)
    {
        $obj = new \stdClass();
        $user = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        $cookies = $user->getCookies()[0];
        $em = $this->getDoctrine()->getManager();
        if ($cookies == null) {
            $cookies = (new Cookie())
                ->setUser($user)
                ->setCookies(0)
                ->setFiledLevel(1)
                ->setVillagerLevel(0)
                ->setGolemLevel(0)
                ->setGolemBoost(300);
            $em->persist($cookies);
            $em->flush();
        }
        $cookiesNumber = $request->get("cookie");
        $fieldLevel = $request->get("field");
        $villagerLevel = $request->get('villager');
        $golemLevel = $request->get("golemLevel");
        $golemBoost = $request->get("golemBoost");
        if($cookiesNumber == null || $fieldLevel == null || $villagerLevel == null || $golemBoost == null || $golemLevel == null){
            $obj->state = 0;
            $obj->err = "Missing value";
        }else{
            ($cookies)
                ->setCookies($cookiesNumber)
                ->setFiledLevel($fieldLevel)
                ->setVillagerLevel($villagerLevel)
                ->setGolemLevel($golemLevel)
                ->setGolemBoost($golemBoost);
            $em->flush();
            if ($cookiesNumber >= 1000000000 && !$user->getIsCookieMaster()){
                // 1000000000
                // 1000018718
                $user->setIsCookieMaster(true);
                $obj->cookieMaster = 1;
                $em->flush();
            }
            $obj->state = 1;
        }
        return new JsonResponse($obj);
    }
}
