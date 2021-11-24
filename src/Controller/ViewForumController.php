<?php

namespace App\Controller;

use App\Entity\Forum;
use App\Repository\ForumRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/view")
 */
class ViewForumController extends AbstractController
{
    /**
     * @Route("/forum/{id}", name="view_forum")
     */
    public function index(Request $request, ForumRepository $forumRepository, int $id, PaginatorInterface $paginator)
    {
        $forum = $forumRepository->find($id);
        if ($forum == null) {
            return $this->redirectToRoute('main');
        }
        if (!$forum->getIsActive()){
            return $this->redirectToRoute('main');
        }

        if ($forum->getNonPined() != null){
            $topicPaginator = $paginator->paginate(
                $forum->getNonPined(),
                $request->query->getInt('page', 1),
                10
            );
        }else{
            $topicPaginator = null;
        }

        return $this->render('view_forum/index.html.twig', [
            'forum' => $forum,
            'paginator' => $topicPaginator
        ]);
    }
}
