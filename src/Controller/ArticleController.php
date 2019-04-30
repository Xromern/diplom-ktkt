<?php

namespace App\Controller;

use App\Entity\Advertisement;
use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="articles")
     */
    public function showArticles()
    {

        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        $advertisements = $this->getDoctrine()
                ->getRepository(Advertisement::class)
            ->findAll();

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'advertisement'=>$advertisements
        ]);
    }

    /**
     * @Route("/article/{id}", name="article")
     */
    public function showArticle(Article $article,\Symfony\Component\HttpFoundation\Request $request)
    {
        dump($article);

        $doctrine = $this->getDoctrine();

        $advertisements = $doctrine
            ->getRepository(Advertisement::class)
            ->findAll();

        $comment = new Comment();

        $formComment = $this->createForm(CommentType::class,$comment);

        $formComment->handleRequest($request);

        dump($comment);
        if ($formComment->isSubmitted() && $formComment->isValid()) {

            $comment->setArticle($article);

            $entityManager = $doctrine->getManager();
        //    dd($comment);
            $entityManager->persist($comment);
            $entityManager->flush();

        }


        return $this->render('article/show-one-article.html.twig', [
            'article' => $article,
            'advertisement'=>$advertisements,
            'formComment'=>$formComment->createView(),
        ]);
    }
}
