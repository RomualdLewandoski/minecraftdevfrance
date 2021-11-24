<?php

namespace App\Controller;

use App\Entity\LikeWall;
use App\Entity\User;
use App\Entity\UserWall;
use App\Repository\LikeWallRepository;
use App\Repository\UserRepository;
use App\Repository\UserWallRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ViewUserController extends AbstractController
{
    /**
     * @Route("/view/user/{username}", name="view_user")
     */
    public function index(Request $request, User $user, UserWallRepository $userWallRepository, PaginatorInterface $paginator)
    {
        $cred = $this->getUser();
        if ($user == null) {
            return $this->redirectToRoute("main");
        }

        if ($cred != null) {
            if ($user->getUsername() == $cred->getUsername()) {
                return $this->redirectToRoute('profile');
            }
        }

        if ($cred == null) {
            if ($user->getUserInfo() != null && !$user->getUserInfo()->getIsPublic()) {
                return $this->redirectToRoute('main');
            }
        }

        $wallPaginator = $paginator->paginate(
            $userWallRepository->findBy(['User' => $user], ['postedAt' => "DESC"]),
            $request->query->getInt('wallPage', 1),
            5
        );
        $wallPaginator->setPaginatorOptions([
            $paginator::PAGE_PARAMETER_NAME => "wallPage"
        ]);

        $activityPaginator = $paginator->paginate(
            $user->getActivities(),
            $request->query->getInt("activityPage", 1),
            5
        );
        $activityPaginator->setPaginatorOptions([
            $paginator::PAGE_PARAMETER_NAME => "activityPage"

        ]);

        $topicPaginator = $paginator->paginate(
            $user->getTopics(),
            $request->query->getInt("topicPage", 1),
            5
        );
        $topicPaginator->setPaginatorOptions([
            $paginator::PAGE_PARAMETER_NAME => "topicPage"

        ]);


        $trophyPaginator = $paginator->paginate(
            $user->getUserTrophies(),
            $request->query->getInt('trophyPage', 1),
            10
        );
        $trophyPaginator->setPaginatorOptions([
            $paginator::PAGE_PARAMETER_NAME => "trophyPage"
        ]);

        return $this->render('view_user/index.html.twig', [
            'user' => $user,
            'walls' => $wallPaginator,
            'activities' => $activityPaginator,
            'topics' => $topicPaginator,
            'trophies' => $trophyPaginator

        ]);
    }

    /**
     * @Route("/like/wall/{id}", name="view_user_like")
     */
    public function likeWall(UserWall $wall, LikeWallRepository $likeWallRepository, UserRepository $userRepository)
    {
        $cred = $this->getUser();
        if ($cred == null) {
            return $this->redirectToRoute("main");
        }
        $user = $userRepository->findOneBy(['username' => $cred->getUsername()]);
        if ($user == null) {
            return $this->redirectToRoute("main");
        }
        $em = $this->getDoctrine()->getManager();
        $isLiked = $likeWallRepository->findOneBy(['postWall' => $wall, 'author' => $user, 'isLike' => true]);
        if ($isLiked != null) {
                       $em->remove($isLiked);

        } else {
            $isDislike = $likeWallRepository->findOneBy(['postWall' => $wall, 'author' => $user, 'isDislike' => true]);
            if ($isDislike != null) {
                $isDislike->setIsDislike(false);
                $isDislike->setIsLike(true);



            } else {
                $like = (new LikeWall())
                    ->setAuthor($user)
                    ->setPostWall($wall)
                    ->setIsLike(true);
                $em->persist($like);
            }
        }
        $em->flush();
        $wall->getUser()->setTotalLike($userRepository->getTotalLike($wall->getUser()));
        $em->flush();
        return $this->redirectToRoute('view_user', ['username' => $wall->getUser()->getUsername()]);
    }

    /**
     * @Route("/dislike/wall/{id}", name="view_user_dislike")
     */
    public function dislikeWall(UserWall $wall, LikeWallRepository $likeWallRepository, UserRepository $userRepository)
    {
        $cred = $this->getUser();
        if ($cred == null) {
            return $this->redirectToRoute("main");
        }
        $user = $userRepository->findOneBy(['username' => $cred->getUsername()]);
        if ($user == null) {
            return $this->redirectToRoute("main");
        }
        $em = $this->getDoctrine()->getManager();
        $isDislike = $likeWallRepository->findOneBy(['postWall' => $wall, 'author' => $user, 'isDislike' => true]);
        if ($isDislike != null) {
            $em->remove($isDislike);

        } else {
            $isLike = $likeWallRepository->findOneBy(['postWall' => $wall, 'author' => $user, 'isLike' => true]);
            if ($isLike != null) {
                $isLike->setIsLike(false);
                $isLike->setIsDislike(true);

            } else {
                $like = (new LikeWall())
                    ->setAuthor($user)
                    ->setPostWall($wall)
                    ->setIsDislike(true);
                $em->persist($like);
            }
        }
        $em->flush();
        $wall->getUser()->setTotalLike($userRepository->getTotalLike($wall->getUser()));
        $em->flush();
        return $this->redirectToRoute('view_user', ['username' => $wall->getUser()->getUsername()]);
    }
}
