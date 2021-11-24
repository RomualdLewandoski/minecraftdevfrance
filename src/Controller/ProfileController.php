<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\UserWall;
use App\Form\WallFormType;
use App\Repository\ReplyRepository;
use App\Repository\TopicRepository;
use App\Repository\UserRepository;
use App\Repository\UserWallRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function index(Request $request, UserRepository $userRepository, UserWallRepository $userWallRepository, PaginatorInterface $paginator)
    {
        $userCredentials = $this->getUser();
        $user = $userRepository->findOneBy(["username" => $userCredentials->getUsername()]);
        if ($user == null) {
            return $this->redirectToRoute("main");
        } else {
            $wall = new UserWall();
            $form = $this->createForm(WallFormType::class, $wall);
            $form->handleRequest($request);
            $em = $this->getDoctrine()->getManager();

            if ($form->isSubmitted() && $form->isValid()) {
                $wall->setPostedAt(new \DateTime('now'));
                $wall->setUser($user);
                $em->persist($wall);
                $em->flush();
                $activity = (new Activity())
                    ->setAuthor($user)
                    ->setDate(new \DateTime('now'))
                    ->setTargetIdWall($wall)
                    ->setType(2);
                $em->persist($activity);
                $em->flush();
                return $this->redirectToRoute("profile");
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

            return $this->render('profile/index.html.twig', [
                'user' => $user,
                'form' => $form->createView(),
                'walls' => $wallPaginator,
                'activities' => $activityPaginator,
                'topics' => $topicPaginator,
                'trophies' => $trophyPaginator
            ]);
        }

    }

    /**
     * @Route("/profile/wall/edit/{id}", name="edit_wall")
     */
    public function editWall(Request $request, UserRepository $userRepository, UserWallRepository $userWallRepository, int $id)
    {
        $userCredentials = $this->getUser();
        $user = $userRepository->findOneBy(["username" => $userCredentials->getUsername()]);
        if ($user == null) {
            return $this->redirectToRoute("main");
        } else {
            $wall = $userWallRepository->find($id);
            if ($wall->getUser() != $user) {
                return $this->redirectToRoute("main");
            } else {
                $form = $this->createForm(WallFormType::class, $wall);
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    $em->flush();
                    return $this->redirectToRoute("profile");
                }
                return $this->render('profile/editWall.html.twig', [
                    'user' => $user,
                    'form' => $form->createView(),
                ]);
            }
        }
    }

    /**
     * @Route("/profile/wall/delete/{id}", name="delete_wall")
     */
    public function deleteSelfWall(Request $request, UserRepository $userRepository, UserWallRepository $userWallRepository, int $id)
    {
        $userCredentials = $this->getUser();
        $user = $userRepository->findOneBy(["username" => $userCredentials->getUsername()]);
        if ($user == null) {
            return $this->redirectToRoute("main");
        } else {
            $wall = $userWallRepository->find($id);
            $target = $wall->getUser()->getUsername();
            $flag = false;
            if ($wall->getUser() != $user) {
                foreach ($user->getUserRanks() as $rank) {
                    if ($rank->getCanManageWall()) {
                        $flag = true;
                    }
                }
            } else {
                $flag = true;
            }
            if ($flag) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($wall);
                $em->flush();
                $wall->getUser()->setTotalLike($userRepository->getTotalLike($wall->getUser()));
                $em->flush();
                return $this->redirectToRoute('view_user', ['username' => $target]);
            } else {
                return $this->redirectToRoute("main");
            }
        }
    }

    /**
     * @Route("/profile/api/active", name="api_active")
     */
    public function active(UserRepository $userRepository)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        $user->setLastActive(time());
        $em->flush();
        $obj = new \stdClass();
        $obj->state = 1;
        return new JsonResponse($obj);
    }
}
