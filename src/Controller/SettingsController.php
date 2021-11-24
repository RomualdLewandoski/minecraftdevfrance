<?php

namespace App\Controller;

use App\Entity\UserInfo;
use App\Form\UserInfoType;
use App\Form\UserSettingsType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/profile")
 */
class SettingsController extends AbstractController
{
    /**
     * @Route("/settings", name="settings")
     */
    public function index(Request $request, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder)
    {
        $userCredentials = $this->getUser();
        $user = $userRepository->findOneBy(["username" => $userCredentials->getUsername()]);
        if ($user == null) {
            return $this->redirectToRoute("main");
        } else {
            $em = $this->getDoctrine()->getManager();
            $userInfo = $user->getUserInfo();
            if ($userInfo == null){
                $userInfo = new UserInfo();
            }
            $userSettings = $this->createForm(UserSettingsType::class, $user);
            $userSettings->handleRequest($request);
            $infoForm = $this->createForm(UserInfoType::class, $userInfo);
            $infoForm->handleRequest($request);

            if ($userSettings->isSubmitted() && $userSettings->isValid()) {
                $checkPass = $passwordEncoder->isPasswordValid($userCredentials, $userSettings->get('password')->getData());
                if ($checkPass) {
                    $uow = $em->getUnitOfWork();
                    $oldUser = $uow->getOriginalEntityData($user);
                    $newPass = $userSettings->get('newPass')->getData();
                    $signature = $userSettings->get('signature')->getData();
                    $flag = false;
                    if ($newPass != null && trim($newPass) != "") {
                        $passCrypt = $passwordEncoder->encodePassword($userCredentials, $newPass);
                        $user->setPassword($passCrypt);
                        $flag = true;
                    }
                    if ($oldUser['email'] != $userSettings->get('email')->getData()) {
                        $getByEmail = $userRepository->findBy(['email' => $userSettings->get('email')->getData()]);
                        if ($getByEmail == null) {
                            $user->setEmail($userSettings->get('email')->getData());
                            $flag = true;
                        } else {
                            $user->setEmail($oldUser['email']);
                            $error = "email déja utilisé";
                            $this->addFlash('error', $error);

                            $flag = false;
                        }
                    }
                    if ($oldUser['useDoomFont'] != $userSettings->get('useDoomFont')->getData()){
                        $flag = true;
                    }
                    if ($oldUser['signature'] != $signature){
                        $user->setSignature($signature);
                        $flag=true;
                    }
                    if ($oldUser['isMinecraftAvatar'] != $userSettings->get('isMinecraftAvatar')->getData()){
                        $user->setIsMinecraftAvatar( $userSettings->get('isMinecraftAvatar')->getData());
                        $flag = true;
                    }
                    if ($flag) {
                        $em->flush();
                        $this->addFlash('success', "Paramètres sauvegardés");
                    }

                } else {
                    $error = "Mot de passe invalide";
                    $this->addFlash('error', $error);

                }
            }

            if ($infoForm->isSubmitted() && $infoForm->isValid()) {
                dump($userInfo);
                if ($user->getUserInfo() == null) {
                    $userInfo->setUser($user);
                    $em->persist($userInfo);

                }
                $em->flush();
                $this->addFlash('success', "Informations sauvegardés");

            }

            return $this->render('settings/index.html.twig', [
                'userSettings' => $userSettings->createView(),
                'userInfo' => $infoForm->createView()
            ]);

        }

    }
}
