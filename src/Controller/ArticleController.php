<?php

namespace App\Controller;

use App\Entity\Advertisement;
use App\Entity\Article;
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
        dump($articles);
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'advertisement'=>$advertisements
        ]);
    }

    /**
     * @Route("/article/{id}", name="article")
     */
    public function showArticle(Article $article)
    {
        dump($article);
        $advertisements = $this->getDoctrine()
            ->getRepository(Advertisement::class)
            ->findAll();

        return $this->render('article/show-one-article.html.twig', [
            'article' => $article,
            'advertisement'=>$advertisements
        ]);
    }
}
