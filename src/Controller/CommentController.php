<?php

namespace App\Controller;


use App\Entity\Article;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Comment;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service;
use Symfony\Component\Validator\ValidatorBuilder;

/**
 * Class CommentController
 * @package App\Controller
 * @ORM\Entity
 * @ORM\Table(name="comment_controller")
 */
class CommentController extends AbstractController
{
    /**
     *
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
     *
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/updateComment", name="updateComment", methods="POST")
     */
    public function updateComment(Request $request, ObjectManager $manager, UserInterface $user= null)
    {
        Service\Helper::isEmpty($user);

        $comment = $manager->getRepository(Comment::class)->findByUser($request->get('comment_id'),$user->getId())[0];

        Service\Helper::isEmpty($comment);
        Service\Helper::validate($comment);
       $comment->setText($request->get('text'));

       $manager->persist($comment);

       $manager->flush();

        return new JsonResponse(array('type' => 'info','message'=>'Коментар успішно змінено.'));
    }

    /**
     * @param Request $request
     * @param ObjectManager $manager
     * @param UserInterface|null $user
     * @return JsonResponse
     * @Route("/deleteComment", name="deleteComment", methods="POST")
     */
    public function deleteComment(Request $request, ObjectManager $manager, UserInterface $user = null)
    {
        Service\Helper::isEmpty($user);

        $comment = $manager->getRepository(Comment::class)->findByUser($request->get('comment_id'),$user->getId())[0];

        Service\Helper::isEmpty($comment);

        $manager->remove($comment);

        $manager->flush();

        return new JsonResponse(array('type' => 'info','message'=>'Коментар успішно видалено.'));
    }

    /**
     * @Route("/addComment", name="addComment", methods="POST")
     */
    public function addComment(Request $request, ObjectManager $manager, UserInterface $user= null)
    {
        Service\Helper::isEmpty($user);
        $comment = new Comment();

        $article = $manager->getRepository(Article::class)->find($request->get('article_id'));
        Service\Helper::isEmpty($article);

        $comment->setText($request->get('text'));
        $comment->setUser($user);
        $comment->setArticle($article);

        Service\Helper::validate($comment);

        $manager->persist($comment);
        $manager->flush();

        return new JsonResponse(array('type' => 'info','message'=>'Коментар додано.'));

    }

    /**
     * @Route("/commentAjaxPaginate", name="showComments", methods={"POST"})
     */
    public function commentAjaxPaginate(Request $request, ObjectManager $manager,PaginatorInterface $paginator)
    {

       $paginationComment =  Service\ArticleManager::getPaginateCommentsForArticle(
           $manager,
           $request->get('id'),
           $request->get('page', 1),
           $paginator);

        $commentRender = $this->render('article/comment.html.twig', [
            'comments'=>$paginationComment
        ]);

        return ($commentRender);

    }
}
