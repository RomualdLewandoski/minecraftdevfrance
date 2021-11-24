<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\ConversationMeta;
use App\Entity\Message;
use App\Entity\MessageMeta;
use App\Form\MessageType;
use App\Repository\ConversationMetaRepository;
use App\Repository\ConversationRepository;
use App\Repository\MessageMetaRepository;
use App\Repository\MessageRepository;
use App\Repository\SystemMessageRepository;
use App\Repository\UserRepository;
use App\Twig\McSkin;
use Doctrine\Common\Collections\ArrayCollection;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InboxController extends AbstractController
{
    /**
     * @Route("/inbox", name="inbox")
     */
    public function index(Request $request, UserRepository $userRepository, PaginatorInterface $paginator, ConversationMetaRepository $conversationMetaRepository)
    {
        $user = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        $sysMessages = $user->getSystemMessages();
        $countSysUnread = 0;
        foreach ($sysMessages as $message) {
            if (!$message->getIsRead()) {
                $countSysUnread++;
            }
        }


        $convPaginator = $paginator->paginate(
            $conversationMetaRepository->findBy(['participants' => $user]),
            $request->query->getInt('page', 1),
            15
        );
        if ($convPaginator->count() == 0) {
            if ($request->query->get('page') != null && $request->query->get('page') > 1) {
                return $this->redirectToRoute('inbox');
            }
        }
        return $this->render('inbox/index.html.twig', [
            'sysMessageCount' => $countSysUnread,
            'messages' => $convPaginator
        ]);
    }

    /**
     * @Route("/inbox/system", name="inbox_system")
     */
    public function system(Request $request, UserRepository $userRepository, PaginatorInterface $paginator, SystemMessageRepository $systemMessageRepository)
    {
        $user = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        $sysMessages = $user->getSystemMessages();
        $countSysUnread = 0;
        foreach ($sysMessages as $message) {
            if (!$message->getIsRead()) {
                $countSysUnread++;
            }
        }
        $convPaginator = $paginator->paginate(
            $sysMessages,
            $request->query->getInt('page', 1),
            15
        );
        if ($convPaginator->count() == 0) {
            if ($request->query->get('page') != null && $request->query->get('page') > 1) {
                return $this->redirectToRoute('inbox_system');
            }
        }
        return $this->render('inbox/inboxSystem.html.twig', [
            'sysMessageCount' => $countSysUnread,
            'messages' => $convPaginator
        ]);
    }

    /**
     * @Route("/inbox/system/read/{id}", name="read_system_message")
     */
    public function readSystem(UserRepository $userRepository, SystemMessageRepository $systemMessageRepository, int $id)
    {
        $user = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        $sysMsg = $systemMessageRepository->find($id);
        if ($sysMsg->getTarget() != $user) {
            return $this->redirectToRoute('inbox');
        } else {
            if (!$sysMsg->getIsRead()) {
                $sysMsg->setIsRead(true);
                $sysMsg->setIsNotify(true);
                $em = $this->getDoctrine()->getManager();
                $em->flush();
            }
            $sysMessages = $user->getSystemMessages();
            $countSysUnread = 0;
            foreach ($sysMessages as $message) {
                if (!$message->getIsRead()) {
                    $countSysUnread++;
                }
            }
            return $this->render('inbox/readSystemp.html.twig', [
                'sysMessageCount' => $countSysUnread,
                'message' => $sysMsg
            ]);
        }
    }

    /**
     * @Route("/inbox/api/users" , name="listUser")
     */
    public function listUsers(Request $request, UserRepository $userRepository)
    {
        $requester = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        $obj = new \stdClass();
        $arr = [];
        $search = $request->get('search');
        foreach ($userRepository->getWhereUsernameLike($search) as $user) {
            if ($requester != $user) {
                $temp = new \stdClass();
                $temp->username = $user->getUsername();
                $temp->email = $user->getEmail();
                $temp->isMc = $user->getIsMinecraftAvatar();
                $temp->mc = $user->getUserInfo()->getMinecraft();
                $temp->skin = (new McSkin())->getMcSkin($temp->mc, "", "with: 80px!important;");
                $temp->id = $user->getId();
                $temp->online = $user->isOnline();
                array_push($arr, $temp);
            }
        }
        $obj->users = $arr;
        return new JsonResponse($obj);
    }

    /**
     * @Route("/inbox/create/", name="createConv", methods={"POST"})
     */
    public function createConv(Request $request, UserRepository $userRepository)
    {
        $user = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        $targetId = $request->request->get('target');
        if ($targetId == null) {
            return $this->redirectToRoute('inbox');
        }
        $target = $userRepository->find($targetId);
        if ($target == null) {
            return $this->redirectToRoute('inbox');
        } else {
            $targetMetas = $target->getConversationMetas();
            $flag = false;
            $conversation = null;
            foreach ($targetMetas as $targetMeta) {
                $conv = $targetMeta->getConversation();
                foreach ($conv->getConversationMetas() as $conversationMeta) {
                    if ($conversationMeta->getParticipants() == $user) {
                        $conversation = $conv;
                        $flag = true;
                        break;
                    }
                }
            }
            if ($flag) {
                return $this->redirectToRoute('read_message', ["id" => $conversation->getId()]);
            } else {
                $em = $this->getDoctrine()->getManager();
                $conversation = (new Conversation())
                    ->setCreatedBy($user);
                $em->persist($conversation);
                $em->flush();
                $meta1 = (new ConversationMeta())
                    ->setConversation($conversation)
                    ->setParticipants($user);
                $em->persist($meta1);
                $em->flush();
                $meta2 = (new ConversationMeta())
                    ->setConversation($conversation)
                    ->setParticipants($target);
                $em->persist($meta2);
                $em->flush();
                return $this->redirectToRoute('read_message', ["id" => $conversation->getId()]);
            }
        }
    }

    /**
     * @Route("/inbox/read/{id}", name="read_message")
     */
    public function read(Request $request, UserRepository $userRepository, ConversationRepository $conversationRepository, MessageRepository $messageRepository, int $id)
    {
        $user = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        $conv = $conversationRepository->find($id);
        if ($conv == null OR !$user->isParticipant($conv)) {
            return $this->redirectToRoute('inbox');
        }
        $messagesTempo = $messageRepository->findBy(['conversation' => $conv], ['postedAt' => "DESC"], 10);

        $recCollection = new ArrayCollection($messagesTempo);
        $iterator = $recCollection->getIterator();
        $iterator->uasort(function ($first, $second) {
            return $first->getPostedAt() > $second->getPostedAt() ? 1 : -1;
        });
        $messages = $iterator;

        $em = $this->getDoctrine()->getManager();
        foreach ($messages as $message) {
            foreach ($message->getMessageMetas() as $messageMeta) {
                if ($messageMeta->getUser() == $user) {
                    $flag = false;
                    if (!$messageMeta->getIsRead()) {
                        $messageMeta->setIsRead(true);
                        $flag = true;
                    }
                    if (!$messageMeta->getIsNotify()) {
                        $messageMeta->setIsNotify(true);
                        $flag = true;
                    }
                    if ($flag) {
                        $em->flush();
                    }
                }
            }
        }

        $sysMessages = $user->getSystemMessages();
        $countSysUnread = 0;
        foreach ($sysMessages as $message) {
            if (!$message->getIsRead()) {
                $countSysUnread++;
            }
        }
        $newMsg = new Message();
        $form = $this->createForm(MessageType::class, $newMsg);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newMsg->setAuthor($user);
            $newMsg->setConversation($conv);
            $newMsg->setPostedAt(new \DateTime('now'));
            $em->persist($newMsg);
            $em->flush();
            foreach ($conv->getConversationMetas() as $convMeta) {
                if ($convMeta->getParticipants() == $user) {
                    $msgMeta = (new MessageMeta())
                        ->setIsNotify(true)
                        ->setIsRead(true)
                        ->setUser($user)
                        ->setMessage($newMsg);

                } else {
                    $canNotify = !$convMeta->getParticipants()->isOnline();
                    $msgMeta = (new MessageMeta())
                        ->setIsNotify($canNotify)
                        ->setIsRead(false)
                        ->setUser($convMeta->getParticipants())
                        ->setMessage($newMsg);

                }
                $em->persist($msgMeta);
                $em->flush();
            }
            return $this->redirectToRoute('read_message', ['id' => $conv->getId()]);

        }

        return $this->render('inbox/read.html.twig', ['sysMessageCount' => $countSysUnread,
            'messages' => $messages,
            'conversation' => $conv,
            'form' => $form->createView(),]);
    }

    /**
     * @Route("inbox/api/send", name="send_msg", methods={"POST"})
     */
    public function sendMessage(Request $request, UserRepository $userRepository, ConversationRepository $conversationRepository)
    {
        $user = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        $convId = $request->request->get('convId');
        $obj = new \stdClass();
        if ($convId == null) {
            $obj->state = 0;
            $obj->err = "Des champs sont manquants dans le formulaire de message";
        } else {
            $conv = $conversationRepository->find($convId);
            if ($conv == null OR !$user->isParticipant($conv)) {
                $obj->state = 0;
                $obj->err = "Vous n'etes pas dans cette conversation";
            }
            $em = $this->getDoctrine()->getManager();

            $msgContent = $request->request->get('message');
            if ($msgContent == null) {
                $obj->state = 0;
                $obj->err = "Des champs sont manquants dans le formulaire de message";

            } else {
                $newMsg = new Message();
                $newMsg->setAuthor($user);
                $newMsg->setConversation($conv);
                $newMsg->setPostedAt(new \DateTime('now'));
                $newMsg->setContent($msgContent);
                $em->persist($newMsg);
                $em->flush();
                foreach ($conv->getConversationMetas() as $convMeta) {
                    if ($convMeta->getParticipants() == $user) {
                        $msgMeta = (new MessageMeta())
                            ->setIsNotify(true)
                            ->setIsRead(true)
                            ->setUser($user)
                            ->setMessage($newMsg);

                    } else {
                        $canNotify = !$convMeta->getParticipants()->isOnline();
                        $msgMeta = (new MessageMeta())
                            ->setIsNotify($canNotify)
                            ->setIsRead(false)
                            ->setUser($convMeta->getParticipants())
                            ->setMessage($newMsg);

                    }
                    $em->persist($msgMeta);
                    $em->flush();
                }
                $obj->state = 1;
                $obj->id = $newMsg->getId();
                $obj->date = $newMsg->getPostedAt();
            }
        }
        return new JsonResponse($obj);
    }

    /**
     * @Route("inbox/api/unread/", name="get_unread",methods={"POST"})
     */
    public function getUnread(Request $request, UserRepository $userRepository, ConversationRepository $conversationRepository, MessageRepository $messageRepository)
    {
        $user = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        $convId = $request->request->get('convId');
        $obj = new \stdClass();
        if ($convId == null) {
            $obj->state = 0;
            $obj->err = "Des champs sont manquants dans le formulaire de message";
        } else {
            $conv = $conversationRepository->find($convId);
            if ($conv == null OR !$user->isParticipant($conv)) {
                $obj->state = 0;
                $obj->err = "Vous n'etes pas dans cette conversation";
            }
            $em = $this->getDoctrine()->getManager();

            $messagesTempo = $messageRepository->findBy(['conversation' => $conv], ['postedAt' => "DESC"], 10);

            $recCollection = new ArrayCollection($messagesTempo);
            $iterator = $recCollection->getIterator();
            $iterator->uasort(function ($first, $second) {
                return $first->getPostedAt() > $second->getPostedAt() ? 1 : -1;
            });
            $messages = $iterator;

            $finalReturn = [];
            foreach ($messages as $message) {
                if ($message->getAuthor() != $user) {
                    $meta = $message->getMetaForUser($user);

                    if ($meta != null && !$meta->getIsNotify()) {
                        $temp = new \stdClass();
                        $temp->content = $message->getContent();
                        $temp->author = $message->getAuthor()->getUsername();
                        $temp->email = $message->getAuthor()->getEmail();
                        $temp->date = $message->getPostedAt()->getTimestamp();
                        $temp->isMc = $message->getAuthor()->getIsMinecraftAvatar();
                        $temp->mc = $message->getAuthor()->getUserInfo()->getMinecraft();
                        $temp->skin = (new McSkin())->getMcSkin($temp->mc, "avatar rounded-circle mr-0 ml-3 z-depth-1");
                        $temp->id = $message->getId();
                        $meta->setIsNotify(true);
                        $em->flush();
                        array_push($finalReturn, $temp);
                    }
                }
            }
            $obj->state = 1;
            $obj->msg = $finalReturn;

        }
        return new JsonResponse($obj);
    }

    /**
     * @Route("/inbox/api/previous", name="get_previous", methods={"POST"})
     */
    public function getPrevious(Request $request, UserRepository $userRepository, ConversationRepository $conversationRepository, MessageRepository $messageRepository)
    {
        $obj = new \stdClass();
        $user = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        $convId = $request->request->get('convId');
        $offset = $request->request->get('offset');
        if ($convId == null OR $offset == null) {
            $obj->state = 0;
            $obj->err = "Des champs sont manquants dans le formulaire de message";
        } else {
            $conv = $conversationRepository->find($convId);
            if ($conv == null OR !$user->isParticipant($conv)) {
                $obj->state = 0;
                $obj->err = "Vous n'etes pas dans cette conversation";
            }
            $messagesTempo = $messageRepository->findBy(['conversation' => $conv], ['postedAt' => "DESC"], 10, $offset);
            if ($messagesTempo == null) {
                $obj->state = 1;
                $obj->end = true;
            } else {
                $msgs = [];
                foreach ($messagesTempo as $message) {
                    $tempo = new \stdClass();
                    $message->getAuthor() == $user ? $tempo->isAuthor = true : $tempo->isAuthor = false;
                    $tempo->content = $message->getContent();
                    $tempo->author = $message->getAuthor()->getUsername();
                    $tempo->email = $message->getAuthor()->getEmail();
                    $tempo->date = $message->getPostedAt()->getTimestamp();
                    $tempo->isMc = $message->getAuthor()->getIsMinecraftAvatar();
                    $tempo->mc = $message->getAuthor()->getUserInfo()->getMinecraft();
                    $tempo->skin = (new McSkin())->getMcSkin($tempo->mc, "avatar rounded-circle mr-0 ml-3 z-depth-1");
                    $tempo->id = $message->getId();
                    array_push($msgs, $tempo);
                }
                $obj->state = 1;
                $obj->end = false;
                $obj->msg = $msgs;
            }

        }
        return new JsonResponse($obj);
    }

    /**
     * @Route("/inbox/api/read", name="set_read", methods={"POST"})
     */
    public function setRead(Request $request, UserRepository $userRepository, ConversationRepository $conversationRepository, MessageRepository $messageRepository)
    {
        $obj = new \stdClass();
        $user = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        $convId = $request->request->get('convId');
        if ($convId == null) {
            $obj->state = 0;
            $obj->err = "Des champs sont manquants dans le formulaire de message";
        } else {
            $conv = $conversationRepository->find($convId);
            if ($conv == null OR !$user->isParticipant($conv)) {
                $obj->state = 0;
                $obj->err = "Vous n'etes pas dans cette conversation";
            } else {
                $messages = $conv->getMessages();
                $em = $this->getDoctrine()->getManager();
                foreach ($messages as $message) {
                    $meta = $message->getMetaForUser($user);
                    if ($meta != null && (!$meta->getIsRead() || !$meta->getIsNotify())) {
                        $meta->setIsRead(true);
                        $meta->setIsNotify(true);
                        $em->flush();
                    }
                }
                $obj->state = 1;
            }


        }
        return new JsonResponse($obj);
    }

    /**
     * @Route("/inbox/api/count", name="api_countMessages", methods={"GET"})
     */
    public function countMessages(UserRepository $userRepository, SystemMessageRepository $systemMessageRepository, MessageMetaRepository $messageMetaRepository)
    {
        $obj = new \stdClass();
        $user = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        $sysMsg = count($systemMessageRepository->findBy(['target' => $user, 'isRead' => false]));
        $inboxMsg = count($messageMetaRepository->findBy(['user' => $user, 'isRead' => false]));

        $obj->total = $sysMsg + $inboxMsg;
        $obj->inbox = $inboxMsg;
        $obj->sys = $sysMsg;
        return new JsonResponse($obj);
    }

    /**
     * @Route("/inbox/api/isOnline", name="isOnlineUser", methods={"POST"})
     */
    public function isOtherOnline(Request $request, UserRepository $userRepository)
    {
        $targetId = $request->request->get('target');
        $target = $userRepository->find($targetId);
        $obj = new \stdClass();
        if ($target == null) {
            $obj->state = 0;
        } else {
            $obj->state = 1;
            $obj->online = $target->isOnline();
        }

        return new JsonResponse($obj);
    }
}
