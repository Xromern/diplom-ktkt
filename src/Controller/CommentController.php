<?php

namespace App\Controller;


use App\Entity\Advertisement;
use App\Entity\Article;
use App\Entity\Comment;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service;

class CommentController extends AbstractController
{
    /**
     * @Route("/getComment", name="getComment", methods="POST")
     */
    public function getComment(Request $request)
    {
       $textComment =  $this->getDoctrine()
            ->getRepository(Comment::class)
            ->find($request->get('comment_id'))
            ->getText();

       return new \Symfony\Component\HttpFoundation\Response($textComment);
    }

    /**
     * @Route("/updateComment", name="updateComment", methods="POST")
     */
    public function updateComment(Request $request, ObjectManager $manager)
    {

       $comment = $manager->getRepository(Comment::class)
            ->find($request->get('comment_id'));

       $comment->setText($request->get('text'));

       $manager->persist($comment);

       $manager->flush();

       return new \Symfony\Component\HttpFoundation\Response();
    }

    /**
<<<<<<< HEAD
     * @Route("/commentAjaxPaginate", name="showComments", methods={"POST"})
=======
     * @Route("/article/{article_id}", name="showComments", requirements={"article_id":"\d+"})
>>>>>>> 26fa11026878574217ae3c8144756e942f03ca3c
     */
    public function commentAjaxPaginate(Request $request, ObjectManager $manager,PaginatorInterface $paginator)
    {

<<<<<<< HEAD
       $paginationComment =  Service\ArticleManager::getPaginateCommentsForArticle(
           $manager,
           $request->get('id'),
           $request->get('page', 1),
           $paginator);

        $commentRender = $this->render('article/comment.html.twig', [
            'comments'=>$paginationComment
        ]);

        return ($commentRender);
=======


>>>>>>> 26fa11026878574217ae3c8144756e942f03ca3c
    }
}
