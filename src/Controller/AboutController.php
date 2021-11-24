<?php

namespace App\Controller;

use App\Entity\SiteSettings;
use App\Repository\SiteSettingsRepository;
use App\Repository\UserRankRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AboutController extends AbstractController
{
    /**
     * @Route("/about", name="about")
     */
    public function index(UserRankRepository $userRankRepository, SiteSettingsRepository $ssr)
    {
        $settings = $ssr->find(1);
        if ($settings == null){
            $settings = (new SiteSettings())
                ->setCgu("<p>Lorem Ipsum</p>")
                ->setRgpd("<p>Lorem Ipsum</p>");
        }
        return $this->render('about/index.html.twig', [
            'settings' => $settings,
            'ranks' => $userRankRepository->getByPriority(),
        ]);
    }
}
