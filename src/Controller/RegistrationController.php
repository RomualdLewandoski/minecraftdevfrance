<?php

namespace App\Controller;

use App\Entity\Cookie;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\AppAuthenticator;
use ReCaptcha\ReCaptcha;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, AppAuthenticator $authenticator): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('main');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $secret = "6LeiL7cZAAAAAPfp-sC6AQ9GomSVTPXNYWy3pHGh";
            $recaptcha = new ReCaptcha($secret);

            $gRecaptchaResponse = $request->get("g-recaptcha-response");
            $remoteIp = $_SERVER['REMOTE_ADDR'];
            $resp = $recaptcha->verify($gRecaptchaResponse, $remoteIp);
            if ($resp->isSuccess()) {
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
                $user->setCreatedAt(new \DateTime('now'));
                $user->setLastLogin(new \DateTime('now'));
                $user->setTotalLike(0);
                $user->setIsKonami(false);
                $user->setIsDoom(false);
                $user->setUseDoomFont(false);
                $user->setIsCookieMaster(false);
                $user->setLastActive(time());
                $user->setIsBanned(false);
                $user->setWarns(0);
                $user->setSuperLikeCount(0);
                $user->setIsMinecraftAvatar(false);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                $cookie = (new Cookie())
                    ->setUser($user)
                    ->setCookies(0)
                    ->setFiledLevel(1)
                    ->setVillagerLevel(0)
                    ->setGolemLevel(0)
                    ->setGolemBoost(300);
                $entityManager->persist($cookie);
                $entityManager->flush();
                // do anything else you need here, like send an email

                return $guardHandler->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $authenticator,
                    'main' // firewall name in security.yaml
                );
            } else {
                $errors = $resp->getErrorCodes();
                $this->addFlash("verify_email_error", "Captcha " . $errors[0]);
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
