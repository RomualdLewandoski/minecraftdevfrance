<?php

namespace App\Controller;

use App\Entity\ReportReply;
use App\Entity\ReportTopic;
use App\Entity\ReportUser;
use App\Entity\ReportWall;
use App\Entity\SystemMessage;
use App\Repository\ReplyRepository;
use App\Repository\ReportReplyRepository;
use App\Repository\ReportTopicRepository;
use App\Repository\ReportUserRepository;
use App\Repository\ReportWallRepository;
use App\Repository\TopicRepository;
use App\Repository\UserRepository;
use App\Repository\UserWallRepository;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

class ReportController extends AbstractController
{
    /**
     * @Route("/report", name="report")
     */
    public function index(Request $request, UserRepository $userRepository, ReportTopicRepository $reportTopicRepository, ReportReplyRepository $reportReplyRepository, ReportWallRepository $reportWallRepository, ReportUserRepository $reportUserRepository)
    {
        $user = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        $flag = false;
        foreach ($user->getUserRanks() as $rank) {
            if ($rank->getHasRepportPanel()) {
                $flag = true;
                break;
            }
        }
        if (!$flag) {
            return $this->redirectToRoute('main');
        }


        return $this->render('report/index.html.twig', [
            'topicReport' => $reportTopicRepository->findAll(),
            'replyReport' => $reportReplyRepository->findAll(),
            'wallReport' => $reportWallRepository->findAll(),
            'userReport' => $reportUserRepository->findAll(),
        ]);
    }

    /**
     * @Route("/report/delete/{type?null}/{id?0}", name="delete_report", methods={"DELETE"})
     */
    public function deleteReport(Request $request, UserRepository $userRepository, ReportTopicRepository $reportTopicRepository, ReportReplyRepository $reportReplyRepository, ReportWallRepository $reportWallRepository, ReportUserRepository $reportUserRepository, string $type, int $id)
    {
        $report = null;
        $em = $this->getDoctrine()->getManager();
        $user = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        $flag = false;
        foreach ($user->getUserRanks() as $rank) {
            if ($rank->getHasRepportPanel()) {
                $flag = true;
                break;
            }
        }
        if (!$flag) {
            return $this->redirectToRoute('main');
        }
        switch ($type) {
            case "topic":
                $report = $reportTopicRepository->find($id);
                if ($this->isCsrfTokenValid('deletetopic' . $report->getId(), $request->request->get('_token'))) {
                    $em->remove($report);
                    $em->flush();
                }
                break;
            case "reply":
                $report = $reportReplyRepository->find($id);
                if ($this->isCsrfTokenValid('deletereply' . $report->getId(), $request->request->get('_token'))) {
                    $em->remove($report);
                    $em->flush();
                }
                break;
            case"wall":
                $report = $reportWallRepository->find($id);
                if ($this->isCsrfTokenValid('deletewall' . $report->getId(), $request->request->get('_token'))) {
                    $em->remove($report);
                    $em->flush();
                }
                break;
            case "user":
                $report = $reportUserRepository->find($id);
                if ($this->isCsrfTokenValid('deleteuser' . $report->getId(), $request->request->get('_token'))) {
                    $em->remove($report);
                    $em->flush();
                }
                break;
        }
        return $this->redirectToRoute("report");
    }

    /**
     * @Route("/report/read/{type?null}/{id?0}", name="read_report")
     */
    public function readReport(UserRepository $userRepository, ReportTopicRepository $reportTopicRepository, ReportReplyRepository $reportReplyRepository, ReportWallRepository $reportWallRepository, ReportUserRepository $reportUserRepository, string $type, int $id)
    {
        $user = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        $flag = false;
        foreach ($user->getUserRanks() as $rank) {
            if ($rank->getHasRepportPanel()) {
                $flag = true;
                break;
            }
        }
        if (!$flag) {
            return $this->redirectToRoute('main');
        }
        switch ($type) {
            case "topic":
                $report = $reportTopicRepository->find($id);
                if ($report == null) {
                    return $this->redirectToRoute('report');
                }

                break;
            case "reply":
                $report = $reportReplyRepository->find($id);
                if ($report == null) {
                    return $this->redirectToRoute('report');
                }
                break;
            case "wall":
                $report = $reportWallRepository->find($id);
                if ($report == null) {
                    return $this->redirectToRoute('report');
                }
                break;
            case "user":
                $report = $reportUserRepository->find($id);
                if ($report == null) {
                    return $this->redirectToRoute('report');
                }
                break;

            default:
                return $this->redirectToRoute("report");
                break;
        }
        return $this->render('report/read.html.twig', [
            "type" => $type,
            "report" => $report
        ]);
    }

    /**
     * @Route("/report/api/count", name="api_count_report")
     */
    public function countReport(ReportTopicRepository $reportTopicRepository, ReportReplyRepository $reportReplyRepository, ReportWallRepository $reportWallRepository, ReportUserRepository $reportUserRepository)
    {
        $count = 0;
        $count = count($reportTopicRepository->findAll()) + count($reportReplyRepository->findAll()) + count($reportWallRepository->findAll()) + count($reportUserRepository->findAll());
        if ($count > 99) {
            $count = 99;
        }
        $obj = new \stdClass();
        $obj->count = $count;
        return new JsonResponse($obj);
    }

    /**
     * @Route("/report/api/report", name="report_send", methods={"POST"})
     */
    public function sendReport(Request $request, UserRepository $userRepository, TopicRepository $topicRepository, ReportTopicRepository $reportTopicRepository, ReplyRepository $replyRepository, ReportReplyRepository $reportReplyRepository, ReportWallRepository $reportWallRepository, UserWallRepository $userWallRepository, ReportUserRepository $reportUserRepository)
    {
        $obj = new \stdClass();
        $user = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        if ($user == null) {
            $obj->state = 0;
            $obj->err = "Unknown user";
        } else {
            $type = $request->get('type');
            $id = $request->get('id');
            if ($type == null || $id == null) {
                $obj->state = 0;
                $obj->err = "Missing type or id";
            } else {
                $em = $this->getDoctrine()->getManager();
                switch ($type) {
                    case "topic":
                        $topic = $topicRepository->find($id);
                        if ($topic == null) {
                            $obj->state = 0;
                            $obj->err = "Unknown topic";
                        } else {
                            $report = $reportTopicRepository->findOneBy(['author' => $user, "topic" => $topic]);
                            if ($report != null) {
                                $obj->state = 0;
                                $obj->err = "Vous avez déja effectuer un report sur ce topic";
                            } else if ($topic->getAuthor() == $user) {
                                $obj->state = 0;
                                $obj->err = "Vous ne pouvez pas vous signaler vous même";
                            } else {
                                $report = (new ReportTopic())
                                    ->setAuthor($user)
                                    ->setDate(new \DateTime('now'))
                                    ->setTopic($topic)
                                    ->setIsSanction(false)
                                    ->setTopicContent($topic->getMessage());
                                $em->persist($report);
                                $em->flush();
                                $obj->state = 1;
                                $obj->type = "du topic";
                            }

                        }
                        break;
                    case "reply":
                        $reply = $replyRepository->find($id);
                        if ($reply == null) {
                            $obj->state = 0;
                            $obj->err = "Unknown reply";
                        } else {
                            $report = $reportReplyRepository->findOneBy(['author' => $user, "reply" => $reply]);
                            if ($report != null) {
                                $obj->state = 0;
                                $obj->err = "Vous avez déja effectuer un report sur cette réponse";
                            } else if ($reply->getAuthor() == $user) {
                                $obj->state = 0;
                                $obj->err = "Vous ne pouvez pas vous signaler vous même";
                            } else {
                                $report = (new ReportReply())
                                    ->setAuthor($user)
                                    ->setDate(new \DateTime('now'))
                                    ->setReply($reply)
                                    ->setIsSanction(false)
                                    ->setReplyContent($reply->getMessage());
                                $em->persist($report);
                                $em->flush();
                                $obj->state = 1;
                                $obj->type = "de la réponse";
                            }
                        }
                        break;
                    case "wall":
                        $wall = $userWallRepository->find($id);
                        if ($wall == null) {
                            $obj->state = 0;
                            $obj->err = "Unknown wall";
                        } else {
                            $report = $reportWallRepository->findOneBy(['author' => $user, "wall" => $wall]);
                            if ($report != null) {
                                $obj->state = 0;
                                $obj->err = "Vous avez déja effectuer un report sur ce message";
                            } else if ($wall->getUser() == $user) {
                                $obj->state = 0;
                                $obj->err = "Vous ne pouvez pas vous signaler vous même";
                            } else {
                                $report = (new ReportWall())
                                    ->setAuthor($user)
                                    ->setDate(new \DateTime('now'))
                                    ->setWall($wall)
                                    ->setIsSanction(false)
                                    ->setWallText($wall->getText());
                                $em->persist($report);
                                $em->flush();
                                $obj->state = 1;
                                $obj->type = "du message";
                            }
                        }
                        break;
                    case "user":
                        $target = $userRepository->find($id);
                        if ($target == null) {
                            $obj->state = 0;
                            $obj->err = "Unknown target";
                        } else {
                            $report = $reportUserRepository->findOneBy(['author' => $user, "user" => $target]);
                            if ($report != null) {
                                $obj->state = 0;
                                $obj->err = "Vous avez déja effectuer un report sur cet utilisateur";
                            } else if ($target == $user) {
                                $obj->state = 0;
                                $obj->err = "Vous ne pouvez pas vous signaler vous même";
                            } else {
                                $report = (new ReportUser())
                                    ->setAuthor($user)
                                    ->setDate(new \DateTime('now'))
                                    ->setIsSanction(false)
                                    ->setUser($target);
                                $em->persist($report);
                                $em->flush();
                                $obj->state = 1;
                                $obj->type = "de l'utilisateur";
                            }
                        }
                        break;
                }
            }
        }
        return new JsonResponse($obj);
    }

    /**
     * @Route("/report/api/sanction", name="sanction", methods={"POST"})
     */
    public function sanction(Request $request, UserRepository $userRepository, TopicRepository $topicRepository, ReportTopicRepository $reportTopicRepository, ReplyRepository $replyRepository, ReportReplyRepository $reportReplyRepository, ReportWallRepository $reportWallRepository, UserWallRepository $userWallRepository, ReportUserRepository $reportUserRepository)
    {
        $type = $request->request->get('type');
        $targetId = $request->request->get('target');
        $reportId = $request->request->get('report');
        $abuse = $request->request->get('abuse');
        if ($abuse == null OR $abuse == "0"){
            $abuse = false;
        }else{
            $abuse = true;
        }
        $user = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        $flag = false;
        $obj = new \stdClass();
        foreach ($user->getUserRanks() as $rank) {
            if ($rank->getHasRepportPanel()) {
                $flag = true;
                break;
            }
        }
        if (!$flag) {
            $obj->state = 0;
            $obj->err = "Vous n'avez pas l'autorisation requise";
        } else {
            if ($type == null OR $targetId == null OR $reportId == null) {
                $obj->state = 0;
                $obj->err = "Missing type, target or report";
            } else {
                $target = $userRepository->find($targetId);
                if ($target == null) {
                    $obj->state = 0;
                    $obj->err = "Unknown target";
                } else {
                    switch ($type) {
                        case "topic":
                            $report = $reportTopicRepository->find($reportId);
                            if ($report == null) {
                                $obj->state = 0;
                                $obj->err = "Unknown report";
                            } else {
                                if ($report->getAuthor() == $target OR $report->getTopic()->getAuthor() == $target) {
                                    $a = true;
                                    $typeTxt = "sujet";
                                } else {
                                    $a = false;
                                    $obj->state = 0;
                                    $obj->err = "User and report does'nt match";
                                }
                            }

                            break;
                        case "reply":
                            $report = $reportReplyRepository->find($reportId);
                            if ($report == null) {
                                $obj->state = 0;
                                $obj->err = "Unknown report";
                            }
                            if ($report->getAuthor() == $target OR $report->getReply()->getAuthor() == $target) {
                                $a = true;
                                $typeTxt = "réponse";
                            } else {
                                $a = false;
                                $obj->state = 0;
                                $obj->err = "User and report does'nt match";
                            }
                            break;
                        case "wall":
                            $report = $reportWallRepository->find($reportId);
                            if ($report == null) {
                                $obj->state = 0;
                                $obj->err = "Unknown report";
                            }
                            if ($report->getAuthor() == $target OR $report->getWall()->getUser() == $target) {
                                $a = true;
                                $typeTxt = "statut sur votre mur";
                            } else {
                                $a = false;
                                $obj->state = 0;
                                $obj->err = "User and report does'nt match";
                            }
                            break;
                        case "user":
                            $report = $reportUserRepository->find($reportId);
                            if ($report == null) {
                                $obj->state = 0;
                                $obj->err = "Unknown report";
                            }
                            if ($report->getAuthor() == $target OR $report->getUser() == $target) {
                                $a = true;
                                $typeTxt = "profil";
                            } else {
                                $a = false;
                                $obj->state = 0;
                                $obj->err = "User and report does'nt match";
                            }
                            break;
                        default:
                            $obj->state = 0;
                            $obj->err = "Unknown report";
                            break;
                    }
                    if ($a) {
                        if (!$report->getIsSanction()) {
                            $em = $this->getDoctrine()->getManager();
                            $target->setWarns($target->getWarns() + 1);
                            $em->flush();
                            if(!$abuse){
                                $message = "<p><h1 class='text-center'>Notification de warn</h1><br>
                                        Vous avez reçu un avertissement suite a un report de votre " . $typeTxt . " <br><br>
                                        Si il s'agit d'un report de profil merci de verifier que votre profil corresponde a notre règlement.<br>
                                        Dans le cas d'un post sur votre mur, d'un sujet ou d'une réponse, il est possible que ce(tte) dernier(e) soit supprimé(e) ou modifié(e)<br><br>
                                        Ceci est un message automatique vous ne pouvez pas y répondre. Veuillez noter que vous avez " . $target->getWarns() . " avertissement(s) <br>
                                        Si votre compte totalise 4 avertissements il serra automatiquement suspendu pour une durée de 72 heures.
                                        </p>";
                            }else{
                                $message = "<p><h1 class='text-center'>Notification de warn</h1><br>
                                        Vous avez reçu un avertissement suite a un report abusif de " . $typeTxt . " <br><br>
                                        Il faut comprendre que faire un report mobilise une équipe de modération.<br>
                                        Le fait de pratiquer des report sans motif valable fait perdre du temps a l'équipe de modération qui ne peut donc traiter rapidement les réels report<br><br>
                                        Ceci est un message automatique vous ne pouvez pas y répondre. Veuillez noter que vous avez " . $target->getWarns() . " avertissement(s) <br>
                                        Si votre compte totalise 4 avertissements il serra automatiquement suspendu pour une durée de 72 heures.
                                        </p>";
                            }

                            $sysMessage = (new SystemMessage())
                                ->setIsNotify(false)
                                ->setIsRead(false)
                                ->setSendAt(new \DateTime('now'))
                                ->setTarget($target)
                                ->setMessage($message);
                            $em->persist($sysMessage);
                            $report->setIsSanction(true);
                            $em->flush();
                            if ($target->getWarns() >= 4) {
                                $date = new \DateTime('now');
                                $date->setTimestamp($date->getTimestamp() + (72 * 60 * 60));
                                $target->setReleasedAt($date);
                                $target->setWarns(0);
                                $target->setIsBanned(true);
                                $em->flush();
                            }
                            $obj->state = 1;
                        } else {
                            $obj->state = 0;
                            $obj->err = "Une sanction a déjà été appliquée sur ce report";
                        }

                    }

                }

            }
        }
        return new JsonResponse($obj);
    }
}
