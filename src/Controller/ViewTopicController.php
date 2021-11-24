<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\LikeReply;
use App\Entity\LikeTopic;
use App\Entity\Reply;
use App\Entity\SuperLikeReply;
use App\Entity\SuperLikeTopic;
use App\Entity\Topic;
use App\Form\ReplyType;
use App\Form\TopicMoveType;
use App\Form\TopicType;
use App\Repository\BrouillonRepository;
use App\Repository\ForumRepository;
use App\Repository\LikeReplyRepository;
use App\Repository\LikeTopicRepository;
use App\Repository\ReplyRepository;
use App\Repository\SuperLikeReplyRepository;
use App\Repository\SuperLikeTopicRepository;
use App\Repository\TopicRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/view/topic")
 */
class ViewTopicController extends AbstractController
{
    /**
     * @Route("/{id}", name="view_topic")
     */
    public function index(Request $request, TopicRepository $topicRepository, UserRepository $userRepository, int $id, PaginatorInterface $paginator, ReplyRepository $replyRepository)
    {
        $topic = $topicRepository->find($id);
        if ($topic == null) {
            return $this->redirectToRoute('main');
        }
        $em = $this->getDoctrine()->getManager();
        $topic->setNumberView($topic->getNumberView() + 1);
        $em->flush();

        $reply = new Reply();
        $em = $this->getDoctrine()->getManager();
        $moveTopic = $this->createForm(TopicMoveType::class, $topic);
        $moveTopic->handleRequest($request);
        if ($moveTopic->isSubmitted() && $moveTopic->isValid()) {
            if ($this->getUser() == null) {
                return $this->redirectToRoute('view_topic', ['id' => $topic->getId()]);
            }
            $user = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
            $flag = false;
            foreach ($user->getUserRanks() as $rank) {
                if ($rank->getCanManageForum()) {
                    $flag = true;
                    break;
                }
            }
            if ($flag) {
                $em->flush();
            }
            return $this->redirectToRoute('view_topic', ['id' => $topic->getId()]);
        }

        $form = $this->createForm(ReplyType::class, $reply);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->getUser() != null) {

                $reply->setPostedAt(new \DateTime('now'));
                $reply->setTopic($topic);
                $user = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
                $reply->setAuthor($user);
                $topic->setLastReplyAt(new \DateTime('now'));
                $em->persist($reply);
                $em->flush();
                $activity = (new Activity())
                    ->setAuthor($user)
                    ->setDate(new \DateTime('now'))
                    ->setTargetIdReply($reply)
                    ->setType(1);
                $em->persist($activity);
                $em->flush();

                $pagination = $paginator->paginate(
                    $replyRepository->findBy(['topic' => $topic], ['postedAt' => "ASC"]),
                    $request->query->getInt('page', 1),
                    10
                );

                $url = $this->generateUrl(
                        $request->attributes->get('_route'),
                        array_merge(
                            $request->attributes->get('_route_params'),
                            [
                                'page' => ceil($pagination->getTotalItemCount() / $request->query->getInt('perpage', 10))
                            ]
                        )
                    ) . "#replyContent" . $reply->getId();
                $reply->setUrl($url);
                $em->flush();
                return $this->redirect(
                    $url
                );

            }
        }

        if ($topic->getReplies() != null) {
            $replyPaginator = $paginator->paginate(
                $replyRepository->findBy(['topic' => $topic], ['postedAt' => "ASC"]),
                $request->query->getInt('page', 1),
                10
            );
        } else {
            $replyPaginator = null;
        }

        if ($request->get('reply') != null) {
            $count = 1;
            $page = 1;
            $replyId = $request->get('reply');
            foreach ($replyRepository->findBy(['topic' => $topic], ['postedAt' => "ASC"]) as $reply) {
                if ($reply->getId() == $replyId) {
                    break;
                }
                if ($count == 10) {
                    $count = 1;
                    $page++;
                } else {
                    $count++;
                }
            }
            $url = $this->generateUrl(
                    $request->attributes->get('_route'),
                    array_merge(
                        $request->attributes->get('_route_params'),
                        [
                            'page' => $page
                        ]
                    )
                ) . "#replyContent" . $replyId;
            return $this->redirect($url);
        }

        return $this->render('view_topic/index.html.twig', [
            'topic' => $topic,
            'form' => $form->createView(),
            'replies' => $replyPaginator,
            'moveTopic' => $moveTopic->createView(),
        ]);
    }

    /**
     * @Route("/create/{id}", name="create_topic")
     */
    public function createTopic(Request $request, ForumRepository $forumRepository, UserRepository $userRepository, BrouillonRepository $brouillonRepository, int $id)
    {
        if ($this->getUser() == null) {
            return $this->redirectToRoute('main');
        }
        $forum = $forumRepository->find($id);
        if ($forum == null) {
            return $this->redirectToRoute("main");
        }
        $user = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        $flag = false;
        foreach ($user->getUserRanks() as $rank) {
            if ($rank->getCanManageForum()) {
                $flag = true;
                break;
            }
        }
        if ($forum->getIsLocked() && !$flag) {
            return $this->redirectToRoute('view_forum', ['id' => $forum->getId()]);
        }

        $topic = new Topic();
        $brouillon = $brouillonRepository->findOneBy(['author' => $user, 'forum' => $forum]);
        $hasBrouillon = false;
        if ($brouillon != null) {
            $hasBrouillon = true;
            if ($brouillon->getTitre() != null) {
                $topic->setTitre($brouillon->getTitre());
            }
            if ($brouillon->getMessage() != null) {
                $topic->setMessage($brouillon->getMessage());
            }
        }
        $form = $this->createForm(TopicType::class, $topic);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $topic->setPostedAt(new \DateTime('now'));
            $topic->setForum($forum);
            $author = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
            $topic->setAuthor($author);
            $topic->setIsLocked(false);
            $topic->setIsPined(false);
            $topic->setLastReplyAt(new \DateTime('now'));
            $topic->setNumberView(0);
            $topic->setSolution(null);
            $em->persist($topic);
            $em->flush();
            $activity = (new Activity())
                ->setAuthor($author)
                ->setDate(new \DateTime('now'))
                ->setTargetIdTopic($topic)
                ->setType(0);
            $em->persist($activity);

            $realBrouillon = $brouillonRepository->findOneBy(['author' => $user, "forum" => $forum]);
            if ($realBrouillon != null) {
                $em->remove($realBrouillon);
            }

            $em->flush();

            $flag = false;
            foreach ($form->get('tags')->getData() as $tag) {
                $topic->addTag($tag);
                $flag = true;
            }
            if ($flag) {
                $em->flush();
            }
            return $this->redirectToRoute('view_topic', ['id' => $topic->getId()]);
        }

        return $this->render('view_topic/createTopic.html.twig', [
            'form' => $form->createView(),
            'forum' => $forum,
            'hasBrouillon' => $hasBrouillon
        ]);
    }


    /**
     * @Route("/lock/{id}", name="lock_topic")
     */
    public function toggleLock(TopicRepository $topicRepository, UserRepository $userRepository, int $id)
    {
        $topic = $topicRepository->find($id);
        if ($topic == null) {
            return $this->redirectToRoute('main');
        }
        if ($this->getUser() == null) {
            return $this->redirectToRoute('main');
        }
        $user = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        $flag = false;
        foreach ($user->getUserRanks() as $rank) {
            if ($rank->getCanManageForum()) {
                $flag = true;
                break;
            }
        }
        if ($flag) {
            $topic->setIsLocked(!$topic->getIsLocked());
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->redirectToRoute('view_topic', ['id' => $topic->getId()]);
    }

    /**
     * @Route("/pin/{id}", name="pin_topic")
     */
    public function togglePin(TopicRepository $topicRepository, UserRepository $userRepository, int $id)
    {
        $topic = $topicRepository->find($id);
        if ($topic == null) {
            return $this->redirectToRoute('main');
        }
        if ($this->getUser() == null) {
            return $this->redirectToRoute('main');
        }
        $user = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        $flag = false;
        foreach ($user->getUserRanks() as $rank) {
            if ($rank->getCanManageForum()) {
                $flag = true;
                break;
            }
        }
        if ($flag) {
            $topic->setIsPined(!$topic->getIsPined());
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->redirectToRoute('view_topic', ['id' => $topic->getId()]);
    }


    /**
     * @Route("/edit/{id}", name="edit_topic")
     */
    public function editTopic(Request $request, TopicRepository $topicRepository, UserRepository $userRepository, int $id)
    {
        $topic = $topicRepository->find($id);
        if ($topic == null) {
            return $this->redirectToRoute('main');
        }
        if ($this->getUser() == null) {
            return $this->redirectToRoute('main');
        }
        $user = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        $flag = false;
        foreach ($user->getUserRanks() as $rank) {
            if ($rank->getCanManageForum()) {
                $flag = true;
                break;
            }
        }
        if (!$flag) {
            if ($topic->getAuthor()->getUsername() == $this->getUser()->getUsername()) {
                $flag = true;
            }
        }
        if ($flag) {
            $form = $this->createForm(TopicType::class, $topic);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                $topic->setMessage($topic->getMessage() . '<div class="hidden" style="display: none">Edit by' . $this->getUser()->getUsername() . " le " . date("d/m/Y") . " à " . date("H:i") . "</div>");
                $em->flush();
                $flag = false;
                foreach ($topic->getTags() as $tag) {
                    $topic->removeTag($tag);
                }
                foreach ($form->get('tags')->getData() as $tag) {
                    $topic->addTag($tag);
                    $flag = true;
                }
                if ($flag) {
                    $em->flush();
                }
                return $this->redirectToRoute('view_topic', ['id' => $topic->getId()]);
            }

            return $this->render('view_topic/editTopic.html.twig', [
                'form' => $form->createView(),
                'topic' => $topic
            ]);
        }
        return $this->redirectToRoute('view_topic', ['id' => $topic->getId()]);
    }

    /**
     * @Route("/delete/{id}", name="delete_topic")
     */
    public function deleteTopic(TopicRepository $topicRepository, UserRepository $userRepository, int $id)
    {
        $topic = $topicRepository->find($id);
        if ($topic == null) {
            return $this->redirectToRoute('main');
        }
        if ($this->getUser() == null) {
            return $this->redirectToRoute('main');
        }
        $user = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        $flag = false;
        foreach ($user->getUserRanks() as $rank) {
            if ($rank->getCanManageForum()) {
                $flag = true;
                break;
            }
        }
        $forumId = $topic->getForum()->getId();
        if (!$flag) {
            if ($topic->getAuthor()->getUsername() == $this->getUser()->getUsername()) {
                $flag = true;
            }
        }
        if ($flag) {
            $em = $this->getDoctrine()->getManager();
            $author = $topic->getAuthor();
            foreach ($topic->getReplies() as $reply) {
                if ($reply == $topic->getSolution()) {
                    $topic->setSolution(null);
                    $em->flush();
                }
                $em->remove($reply);
                $em->flush();
            }
            $em->remove($topic);
            $em->flush();
            $author->setTotalLike($userRepository->getTotalLike($author));
            $author->setSuperLikeCount($userRepository->getTotalAdminLike($author));
            $em->flush();
            return $this->redirectToRoute('view_forum', ['id' => $forumId]);
        }
        return $this->redirectToRoute('view_topic', ['id' => $topic->getId()]);

    }


    /**
     * @Route("/edit/reply/{id}", name="edit_reply")
     */
    public function editReply(Request $request, ReplyRepository $replyRepository, UserRepository $userRepository, int $id)
    {
        $reply = $replyRepository->find($id);
        if ($reply == null) {
            return $this->redirectToRoute('main');
        }
        if ($this->getUser() == null) {
            return $this->redirectToRoute('main');
        }
        $user = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        $flag = false;
        foreach ($user->getUserRanks() as $rank) {
            if ($rank->getCanManageForum()) {
                $flag = true;
                break;
            }
        }
        if (!$flag) {
            if ($reply->getAuthor()->getUsername() == $this->getUser()->getUsername()) {
                $flag = true;
            }
        }
        if ($flag) {
            $form = $this->createForm(ReplyType::class, $reply);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                $reply->setMessage($reply->getMessage() . '<div class="hidden" style="display: none">Edit by' . $this->getUser()->getUsername() . " le " . date("d/m/Y") . " à " . date("H:i") . "</div>");
                $em->flush();

                return $this->redirectToRoute('view_topic', ['id' => $reply->getTopic()->getId()]);
            }

            return $this->render('view_topic/editReply.html.twig', [
                'form' => $form->createView(),
            ]);
        }
        return $this->redirectToRoute('view_topic', ['id' => $reply->getTopic()->getId()]);
    }


    /**
     * @Route("/delete/reply/{id}", name="delete_reply")
     */
    public function deleteReply(ReplyRepository $replyRepository, UserRepository $userRepository, int $id)
    {
        $reply = $replyRepository->find($id);
        if ($reply == null) {
            return $this->redirectToRoute('main');
        }
        if ($this->getUser() == null) {
            return $this->redirectToRoute('main');
        }
        $user = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        $flag = false;
        foreach ($user->getUserRanks() as $rank) {
            if ($rank->getCanManageForum()) {
                $flag = true;
                break;
            }
        }
        if (!$flag) {
            if ($reply->getAuthor()->getUsername() == $this->getUser()->getUsername()) {
                $flag = true;
            }
        }
        $topicId = $reply->getTopic()->getId();
        if ($flag) {
            $em = $this->getDoctrine()->getManager();
            $author = $reply->getAuthor();
            if ($reply->getTopic()->getSolution() != null && $reply->getTopic()->getSolution() == $reply) {
                $reply->getTopic()->setSolution(null);
            }
            $em->remove($reply);
            $em->flush();
            $author->setTotalLike($userRepository->getTotalLike($author));
            $author->setSuperLikeCount($userRepository->getTotalAdminLike($author));
            $em->flush();
            return $this->redirectToRoute('view_topic', ['id' => $topicId]);
        }
        return $this->redirectToRoute('view_topic', ['id' => $topicId]);
    }


    /**
     * @Route("/like/{id}", name="like_topic")
     */
    public function toggleLike(TopicRepository $topicRepository, UserRepository $userRepository, LikeTopicRepository $likeTopicRepository, int $id)
    {
        $topic = $topicRepository->find($id);
        if ($topic == null) {
            return $this->redirectToRoute('main');
        }
        if ($this->getUser() == null) {
            return $this->redirectToRoute('view_topic', ['id' => $topic->getId()]);
        }
        $user = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        if ($user == null) {
            return $this->redirectToRoute('view_topic', ['id' => $topic->getId()]);
        }
        if ($user == $topic->getAuthor()) {
            return $this->redirectToRoute('view_topic', ['id' => $topic->getId()]);
        }
        $em = $this->getDoctrine()->getManager();
        $flag = null;
        $switch = false;
        $isLiked = $likeTopicRepository->findOneBy(['topic' => $topic, 'author' => $user, 'isLike' => true]);
        if ($isLiked != null) {
            $flag = false;
            $em->remove($isLiked);
        } else {
            $isDislike = $likeTopicRepository->findOneBy(['topic' => $topic, 'author' => $user, 'isDislike' => true]);
            if ($isDislike != null) {
                $isDislike->setIsDislike(false);
                $isDislike->setIsLike(true);
                $flag = true;
                $switch = true;
            } else {
                $like = (new LikeTopic())
                    ->setAuthor($user)
                    ->setTopic($topic)
                    ->setIsLike(true)
                    ->setIsDislike(false);
                $em->persist($like);
                $flag = true;
            }
        }
        $em->flush();
        $topic->getAuthor()->setTotalLike($userRepository->getTotalLike($topic->getAuthor()));
        $topic->getAuthor()->setSuperLikeCount($userRepository->getTotalAdminLike($topic->getAuthor()));

        $em->flush();
        $obj = new \stdClass();
        $obj->like = count($topic->getLikes());
        $obj->action = $flag;
        $obj->switch = $switch;
        $obj->dislike = count($topic->getDislike());
        return new JsonResponse($obj);
    }

    /**
     * @Route("/dislike/{id}", name="dislike_topic")
     */
    public function toggleDislike(TopicRepository $topicRepository, UserRepository $userRepository, LikeTopicRepository $likeTopicRepository, int $id)
    {
        $topic = $topicRepository->find($id);
        if ($topic == null) {
            return $this->redirectToRoute('main');
        }
        if ($this->getUser() == null) {
            return $this->redirectToRoute('view_topic', ['id' => $topic->getId()]);
        }
        $user = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        if ($user == null) {
            return $this->redirectToRoute('view_topic', ['id' => $topic->getId()]);
        }
        if ($user == $topic->getAuthor()) {
            return $this->redirectToRoute('view_topic', ['id' => $topic->getId()]);
        }
        $em = $this->getDoctrine()->getManager();
        $flag = null;
        $switch = false;
        $isDislike = $likeTopicRepository->findOneBy(['topic' => $topic, 'author' => $user, "isDislike" => true]);
        if ($isDislike != null) {
            $flag = false;
            $em->remove($isDislike);
        } else {
            $isLiked = $likeTopicRepository->findOneBy(['topic' => $topic, 'author' => $user, 'isLike' => true]);
            if ($isLiked != null) {
                $flag = true;
                $switch = true;
                $isLiked->setIsLike(false);
                $isLiked->setIsDislike(true);
            } else {
                $like = (new LikeTopic())
                    ->setAuthor($user)
                    ->setTopic($topic)
                    ->setIsLike(false)
                    ->setIsDislike(true);
                $em->persist($like);
                $flag = true;
            }
        }
        $em->flush();
        $topic->getAuthor()->setTotalLike($userRepository->getTotalLike($topic->getAuthor()));
        $topic->getAuthor()->setSuperLikeCount($userRepository->getTotalAdminLike($topic->getAuthor()));

        $em->flush();
        $obj = new \stdClass();
        $obj->like = count($topic->getLikes());
        $obj->action = $flag;
        $obj->switch = $switch;
        $obj->dislike = count($topic->getDislike());
        return new JsonResponse($obj);
    }

    /**
     * @Route("/superlike/{id}", name="superlike_topics")
     */
    public function toggleSuperLikeTopic(TopicRepository $topicRepository, UserRepository $userRepository, SuperLikeTopicRepository $superLikeTopicRepository, int $id)
    {
        $topic = $topicRepository->find($id);
        if ($topic == null) {
            return $this->redirectToRoute('main');
        }
        if ($this->getUser() == null) {
            return $this->redirectToRoute('view_topic', ['id' => $topic->getId()]);
        }
        $user = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        if ($user == null) {
            return $this->redirectToRoute('view_topic', ['id' => $topic->getId()]);
        }
        $canManage = false;
        foreach ($user->getUserRanks() as $role) {
            if ($role->getCanManageForum()) {
                $canManage = true;
                break;
            }
        }
        if (!$canManage) {
            return $this->redirectToRoute('view_topic', ['id' => $topic->getId()]);
        }
        $flag = false;
        $em = $this->getDoctrine()->getManager();
        $superLike = $superLikeTopicRepository->findOneBy(['topic' => $topic, 'author' => $user]);
        if ($superLike != null) {
            $em->remove($superLike);
        } else {
            $superLike = (new SuperLikeTopic())
                ->setTopic($topic)
                ->setAuthor($user);
            $em->persist($superLike);
            $flag = true;
        }
        $em->flush();
        $topic->getAuthor()->setSuperLikeCount($userRepository->getTotalAdminLike($topic->getAuthor()));
        $em->flush();
        $obj = new \stdClass();
        $obj->total = count($topic->getSuperLikeTopics());
        $obj->action = $flag;
        return new JsonResponse($obj);
    }


    /**
     * @Route("/like/reply/", name="like_reply", methods={"POST"})
     */
    public function likeReply(Request $request, UserRepository $userRepository, ReplyRepository $replyRepository, LikeReplyRepository $likeReplyRepository)
    {
        $id = $request->get('id');
        if ($id == null) {
            return $this->redirectToRoute('main');
        }
        $reply = $replyRepository->find($id);
        if ($reply == null) {
            return $this->redirectToRoute('main');
        }
        if ($this->getUser() == null) {
            return $this->redirectToRoute('view_topic', ['id' => $reply->getTopic()->getId()]);
        }
        $user = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        if ($user == null OR ($user == $reply->getAuthor())) {
            return $this->redirectToRoute('view_topic', ['id' => $reply->getTopic()->getId()]);
        }
        $em = $this->getDoctrine()->getManager();
        $flag = null;
        $switch = false;
        $isLiked = $likeReplyRepository->findOneBy(['reply' => $reply, 'author' => $user, 'isLike' => true]);
        if ($isLiked != null) {

            $flag = false;
            $em->remove($isLiked);
        } else {
            $isDisliked = $likeReplyRepository->findOneBy(['reply' => $reply, 'author' => $user, 'isDislike' => true]);
            if ($isDisliked != null) {
                $isDisliked->setIsDislike(false);
                $isDisliked->setIsLike(true);
                $flag = true;
                $switch = true;
            } else {
                $like = (new LikeReply())
                    ->setReply($reply)
                    ->setAuthor($user)
                    ->setIsLike(true)
                    ->setIsDislike(false);
                $em->persist($like);
                $flag = true;
            }
        }
        $em->flush();
        $reply->getAuthor()->setTotalLike($userRepository->getTotalLike($reply->getAuthor()));
        $reply->getAuthor()->setSuperLikeCount($userRepository->getTotalAdminLike($reply->getAuthor()));

        $em->flush();
        $obj = new \stdClass();
        $obj->like = count($reply->getLikes());
        $obj->action = $flag;
        $obj->switch = $switch;
        $obj->dislike = count($reply->getDislike());
        return new JsonResponse($obj);
    }

    /**
     * @Route("/dislike/reply/", name="dislike_reply", methods={"POST"})
     */
    public function dislikeReply(Request $request, UserRepository $userRepository, ReplyRepository $replyRepository, LikeReplyRepository $likeReplyRepository)
    {
        $id = $request->get('id');
        if ($id == null) {
            return $this->redirectToRoute('main');
        }
        $reply = $replyRepository->find($id);
        if ($reply == null) {
            return $this->redirectToRoute('main');
        }
        if ($this->getUser() == null) {
            return $this->redirectToRoute('view_topic', ['id' => $reply->getTopic()->getId()]);
        }
        $user = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        if ($user == null OR ($user == $reply->getAuthor())) {
            return $this->redirectToRoute('view_topic', ['id' => $reply->getTopic()->getId()]);
        }
        $em = $this->getDoctrine()->getManager();
        $flag = null;
        $switch = false;
        $isDisliked = $likeReplyRepository->findOneBy(['reply' => $reply, 'author' => $user, 'isDislike' => true]);
        if ($isDisliked != null) {
            $flag = false;
            $em->remove($isDisliked);
        } else {
            $isLiked = $likeReplyRepository->findOneBy(['reply' => $reply, 'author' => $user, 'isLike' => true]);
            if ($isLiked != null) {
                $isLiked->setIsDislike(true);
                $isLiked->setIsLike(false);
                $flag = true;
                $switch = true;
            } else {
                $like = (new LikeReply())
                    ->setReply($reply)
                    ->setAuthor($user)
                    ->setIsLike(false)
                    ->setIsDislike(true);
                $em->persist($like);
                $flag = true;
            }
        }
        $em->flush();
        $reply->getAuthor()->setTotalLike($userRepository->getTotalLike($reply->getAuthor()));
        $reply->getAuthor()->setSuperLikeCount($userRepository->getTotalAdminLike($reply->getAuthor()));

        $em->flush();
        $obj = new \stdClass();
        $obj->like = count($reply->getLikes());
        $obj->action = $flag;
        $obj->switch = $switch;
        $obj->dislike = count($reply->getDislike());
        return new JsonResponse($obj);
    }

    /**
     * @Route("/superlike/reply/", name="superlike_reply", methods={"POST"})
     */
    public function superLikeReply(Request $request, UserRepository $userRepository, ReplyRepository $replyRepository, SuperLikeReplyRepository $superLikeReplyRepository)
    {
        $id = $request->get('id');
        if ($id == null) {
            return $this->redirectToRoute('main');
        }
        $reply = $replyRepository->find($id);
        if ($reply == null) {
            return $this->redirectToRoute('main');
        }
        if ($this->getUser() == null) {
            return $this->redirectToRoute('view_topic', ['id' => $reply->getTopic()->getId()]);
        }
        $user = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        if ($user == null) {
            return $this->redirectToRoute('view_topic', ['id' => $reply->getTopic()->getId()]);
        }
        $em = $this->getDoctrine()->getManager();
        $flag = null;
        $superLike = $superLikeReplyRepository->findOneBy(['reply' => $reply, 'author' => $user]);
        if ($superLike != null) {
            $em->remove($superLike);
        } else {
            $superLike = (new SuperLikeReply())
                ->setReply($reply)
                ->setAuthor($user);
            $em->persist($superLike);
            $flag = true;
        }
        $em->flush();
        $reply->getAuthor()->setSuperLikeCount($userRepository->getTotalAdminLike($reply->getAuthor()));
        $em->flush();
        $obj = new \stdClass();
        $obj->total = count($reply->getSuperLikeReplies());
        $obj->action = $flag;
        return new JsonResponse($obj);
    }

    /**
     * @Route("/solution/{id}", name="solution_set")
     */
    public function setSolution(UserRepository $userRepository, ReplyRepository $replyRepository, int $id)
    {
        $reply = $replyRepository->find($id);
        if ($reply == null) {
            return $this->redirectToRoute('main');
        }
        if ($this->getUser() == null) {
            return $this->redirectToRoute('view_topic', ['id' => $reply->getTopic()->getId()]);
        }
        $user = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        if ($user == null) {
            return $this->redirectToRoute('view_topic', ['id' => $reply->getTopic()->getId()]);
        }
        $flag = false;
        if ($user == $reply->getTopic()) {
            $flag = true;
        } else {
            foreach ($user->getUserRanks() as $role) {
                if ($role->getCanManageForum()) {
                    $flag = true;
                    break;
                }
            }
        }
        if (!$flag) {
            return $this->redirectToRoute('view_topic', ['id' => $reply->getTopic()->getId()]);
        } else {
            $solution = $reply->getTopic()->getSolution();
            if ($solution != null && $solution->getId() == $reply->getId()) {
                $reply->getTopic()->setSolution(null);
            } else {
                $reply->getTopic()->setSolution($reply);
            }
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('view_topic', ['id' => $reply->getTopic()->getId()]);
        }
    }

    /**
     * @Route("/reactions/", name="getReaction", methods={"POST"})
     */
    public function getReactions(Request $request, TopicRepository $topicRepository, ReplyRepository $replyRepository)
    {
        $obj = new \stdClass();
        $type = $request->get("type");
        $id = $request->get("id");
        if ($type == null || $id == null) {
            $obj->state = 0;
            $obj->err = "type or id missing";
        } else {
            if ($type == "topic") {
                $topic = $topicRepository->find($id);
                if ($topic == null) {
                    $obj->state = 0;
                    $obj->err = "unknown topic id ";
                } else {
                    $arr = [];
                    $likes = $topic->getLikeTopics();
                    foreach ($likes as $like) {
                        $tempo = new \stdClass();
                        if ($like->getIsLike()) {
                            $tempo->type = "like";
                        } else {
                            $tempo->type = "dislike";
                        }
                        $tempo->username = $like->getAuthor()->getUsername();
                        $grav_url = "https://www.gravatar.com/avatar/" . md5(strtolower(trim($like->getAuthor()->getEmail()))) . "?d=mm&s=80";
                        $tempo->email = $grav_url;
                        array_push($arr, $tempo);
                    }
                    $likes = $topic->getSuperLikeTopics();
                    foreach ($likes as $like) {
                        $tempo = new \stdClass();
                        $tempo->type = "superLike";
                        $tempo->username = $like->getAuthor()->getUsername();
                        $grav_url = "https://www.gravatar.com/avatar/" . md5(strtolower(trim($like->getAuthor()->getEmail()))) . "?d=mm&s=80";
                        $tempo->email = $grav_url;
                        array_push($arr, $tempo);
                    }
                    $obj->state = 1;
                    $obj->data = $arr;
                }
            } else if ($type == "reply") {
                $reply = $replyRepository->find($id);
                if ($reply == null) {
                    $obj->state = 0;
                    $obj->err = "unknown reply id ";
                } else {
                    $arr = [];
                    $likes = $reply->getLikeReplies();
                    foreach ($likes as $like) {
                        $tempo = new \stdClass();
                        if ($like->getIsLike()) {
                            $tempo->type = "like";
                        } else {
                            $tempo->type = "dislike";
                        }
                        $tempo->username = $like->getAuthor()->getUsername();
                        $grav_url = "https://www.gravatar.com/avatar/" . md5(strtolower(trim($like->getAuthor()->getEmail()))) . "?d=mm&s=80";
                        $tempo->email = $grav_url;
                        array_push($arr, $tempo);
                    }
                    $likes = $reply->getSuperLikeReplies();
                    foreach ($likes as $like) {
                        $tempo = new \stdClass();
                        $tempo->type = "superLike";
                        $tempo->username = $like->getAuthor()->getUsername();
                        $grav_url = "https://www.gravatar.com/avatar/" . md5(strtolower(trim($like->getAuthor()->getEmail()))) . "?d=mm&s=80";
                        $tempo->email = $grav_url;
                        array_push($arr, $tempo);
                    }
                    $obj->state = 1;
                    $obj->data = $arr;
                }
            } else {
                $obj->state = 0;
                $obj->err = "unknown type";
            }
        }

        return new JsonResponse($obj);
    }
}
