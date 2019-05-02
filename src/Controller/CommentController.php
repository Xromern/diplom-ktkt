<?php

namespace App\Controller;


use App\Entity\Article;
use App\Entity\Comment;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/article/{article_id}", name="showComments", requirements={"article_id":"\d+"})
     */
    public function showComments(Request $request, ObjectManager $manager,PaginatorInterface $paginator)
    {



    }
}
