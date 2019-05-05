<?php

namespace App\Controller;


use App\Entity\Advertisement;
use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use http\Env\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Service;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="articles")
     */
    public function showArticles(\Symfony\Component\HttpFoundation\Request $request,PaginatorInterface $paginator)
    {

        $em = $this->getDoctrine()->getManager();

        $paginationArticle = Service\ArticleManager::getPaginateArticles(
            $em,
            $request->get('page', 1),$paginator);

        $advertisements = $em
            ->getRepository(Advertisement::class)
            ->findAll();

        if($paginationArticle == null){
            return $this->render('Exception/error404.html.twig', [

                'advertisement'=>$advertisements,
                'message_error'=>'Сторінка не знайдена'
            ]);
        }

        return $this->render('article/index.html.twig', [
            'articles' => $paginationArticle,
            'advertisement'=>$advertisements
        ]);
    }

    /**
<<<<<<< HEAD
     * @Route("/articlesAjaxPaginate", name="articlesAjaxPaginate")
=======
     * @Route("/article/{id}", name="article",requirements={"id":"\d+"})
>>>>>>> 26fa11026878574217ae3c8144756e942f03ca3c
     */
    public function articlesAjaxPaginate(\Symfony\Component\HttpFoundation\Request $request,PaginatorInterface $paginator)
    {
<<<<<<< HEAD

        $em = $this->getDoctrine()->getManager();

        $paginationArticle = Service\ArticleManager::getPaginateArticles(
            $em,
            $request->get('page', 1),$paginator);

        if(count($paginationArticle) == 0){
            return $this->render('Exception/error404.html.twig', [

                'message_error'=>'Сторінка не знайдена'
            ]);
        }
=======
>>>>>>> 26fa11026878574217ae3c8144756e942f03ca3c

        return $this->render('article/articles.html.twig', [
            'articles' => $paginationArticle,
        ]);
    }

    /**
     * @Route("/article/{id}", name="article",requirements={"id":"\d+"})
     */
    public function showArticle(\Symfony\Component\HttpFoundation\Request $request,PaginatorInterface $paginator)
    {

        $entityManager =  $this->getDoctrine()->getManager();

        $advertisements = $entityManager
            ->getRepository(Advertisement::class)
            ->findAll();

        $article = $entityManager->getRepository(Article::class)->find($request->get('id'));

<<<<<<< HEAD
        $paginationComment =  Service\ArticleManager::getPaginateCommentsForArticle(
            $entityManager,
            $request->get('id'),
            $request->get('page', 1),$paginator);

=======
        $articlesRepo = $entityManager->getRepository(Comment::class);

        $articlesQuery = $articlesRepo->createQueryBuilder('c')
            ->Where('c.article = :article_id')
            ->orderBy('c.id', 'desc')
            ->setParameter('article_id', $article->getId())
            ->getQuery();

        $paginationComment = $paginator->paginate(
        // Doctrine Query, not results
            $articlesQuery,
            // Define the page parameter
            $request->get('page', 1),
            // Items per page
            3
        );

        if($request->isXmlHttpRequest()) {
            $commentRender = $this->render('article/comment.html.twig', [
                'comments'=>$paginationComment,
            ]);

            return ($commentRender);
        }

        if($article ==null){
            return $this->render('Exception/error404.html.twig', [
                'advertisement'=>$advertisements,
                'message_error'=>'Сторінка не знайдена'
            ]);
        }
>>>>>>> 26fa11026878574217ae3c8144756e942f03ca3c
        $comment = new Comment();

        $formComment = $this->createForm(CommentType::class,$comment, array(
            'action' => $this->generateUrl('article',['id'=>$id]),
        ));

        $formComment->handleRequest($request);

        if ($formComment->isSubmitted() && $formComment->isValid()) {
            $comment->setArticle($article);

            $entityManager->persist($comment);

            $entityManager->flush();

        }

        return $this->render('article/show-one-article.html.twig', [
            'article' => $article,
            'comments'=>$paginationComment,
            'advertisement'=>$advertisements,
            'routeName'=>'showComments',
            'formComment'=>$formComment->createView(),
        ]);
    }
}
