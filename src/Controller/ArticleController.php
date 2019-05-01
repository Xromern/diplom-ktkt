<?php

namespace App\Controller;

use App\Entity\Advertisement;
use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use http\Env\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="articles")
     */
    public function showArticles(\Symfony\Component\HttpFoundation\Request $request,PaginatorInterface $paginator)
    {

        $em = $this->getDoctrine()->getManager();

        $articlesRepo = $em->getRepository(Article::class);

        $advertisements = $this->getDoctrine()
                ->getRepository(Advertisement::class)
            ->findAll();

        $articlesQuery = $articlesRepo->createQueryBuilder('a')
            ->orderBy('a.id','DESC')
            ->getQuery();

        $paginationArticle = $paginator->paginate(
        // Doctrine Query, not results
            $articlesQuery,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            9
        );
        if(count($paginationArticle) == 0){
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
     * @Route("/article/{id}", name="article", requirements={"id":"\d+"})
     */
    public function showArticle($id,\Symfony\Component\HttpFoundation\Request $request,PaginatorInterface $paginator)
    {

        $doctrine = $this->getDoctrine();

        $entityManager = $doctrine->getManager();

        $advertisements = $doctrine
            ->getRepository(Advertisement::class)
            ->findAll();

        $article= $entityManager->getRepository(Article::class)->find($id);

        if($article ==null){
            return $this->render('Exception/error404.html.twig', [

                'advertisement'=>$advertisements,
                'message_error'=>'Сторінка не знайдена'
            ]);
        }
        $comment = new Comment();

        $formComment = $this->createForm(CommentType::class,$comment);

        $formComment->handleRequest($request);

       // dump($comment);
        if ($formComment->isSubmitted() && $formComment->isValid()) {

            $comment->setArticle($article);

            $entityManager->persist($comment);

            $entityManager->flush();

        }
        $articlesRepo = $entityManager->getRepository(Comment::class);

        $articlesQuery = $articlesRepo->createQueryBuilder('c')
            ->Where('c.article = :article_id')
            ->setParameter('article_id', $article->getId())
            ->orderBy('c.id','DESC')
            ->getQuery();

        $paginationComment = $paginator->paginate(
        // Doctrine Query, not results
            $articlesQuery,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            3
        );

        return $this->render('article/show-one-article.html.twig', [
            'article' => $article,
            'comments'=>$paginationComment,
            'advertisement'=>$advertisements,
            'formComment'=>$formComment->createView(),
        ]);
    }
}
