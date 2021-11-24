<?php

namespace App\Controller;

use App\Entity\SiteSettings;
use App\Form\SiteSettingsType;
use App\Repository\SiteSettingsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SiteSettingsController extends AbstractController
{
    /**
     * @Route("/admin/site/settings", name="site_settings")
     */
    public function index(Request $request, SiteSettingsRepository $ssr)
    {
        $siteSettings = $ssr->find(1);
        $flag = true;
        if ($siteSettings == null) {
            $flag = false;
            $siteSettings = new SiteSettings();
        }
        $form = $this->createForm(SiteSettingsType::class, $siteSettings);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if (!$flag) {
                $siteSettings->setId(1);
                $em->persist($siteSettings);
            }
            $em->flush();
            return $this->redirectToRoute('site_settings');
        }

        return $this->render('site_settings/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
