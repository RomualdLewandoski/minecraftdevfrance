<?php

namespace App\Controller;

use App\Entity\SiteSettings;
use App\Repository\CatForumRepository;
use App\Repository\SiteSettingsRepository;
use App\Repository\TopicRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(Request $request, CatForumRepository $catForumRepository, TopicRepository $topicRepository, SiteSettingsRepository $ssr)
    {
        //dump($_SERVER['REMOTE_ADDR'], $request->server->get("HTTP_X_FORWARDED_FOR"));
        //dump($_ENV['DATABASE_URL']);
        $settings = $ssr->find(1);
        if ($settings == null){
            $settings = (new SiteSettings())
                ->setYoutube("https://www.youtube.com/embed/4pOWTTkBflo");
        }
        return $this->render('main/index.html.twig', [
            'settings' => $settings,
            'lastTopic' => $topicRepository->getLast(),
            'cats' => $catForumRepository->getByOrder(),
        ]);
    }

    /**
     * @Route("", name="checkBan", methods={"POST"})
     */
    public function checkBan(Request $request, UserRepository $userRepository)
    {
        $obj = new \stdClass();
        $username = $request->request->get('username');

        return new JsonResponse($obj);
    }
}
