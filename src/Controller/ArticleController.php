<?php

namespace App\Controller;

use App\Entity\JournalGroup;
use App\Entity\Menu;
use App\Entity\Page;
use App\Service\Helper;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Advertisement;
use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="articles")
     */
    public function showArticles(\Symfony\Component\HttpFoundation\Request $request,PaginatorInterface $paginator,UserInterface $user = null)
    {
        $em = $this->getDoctrine()->getManager();
        Service\Helper::generateName();
        $paginationArticle = Service\ArticleManager::getPaginateArticles(
            $em,
            $request->get('page', 1),
            $paginator);

        $advertisements = $em
            ->getRepository(Advertisement::class)
            ->findAll();

        if($paginationArticle == null){
            return $this->render('Exception/error404.html.twig', [

                'advertisement'=>$advertisements,
                'message_error'=>'Сторінка не знайдена',
                'condition'=>false
            ]);
        }

        if($request->isXmlHttpRequest()) {
            return $this->render('article/articles-paginate.html.twig', [
                'articles' => $paginationArticle,
                'ajax' => true,
            ]);
        }else {
            return $this->render('article/articles-paginate.html.twig', [
                'articles' => $paginationArticle,
                'menu'=>Service\Helper::createMenu($em),
                'advertisement' => $advertisements,
                'ajax' => false,
            ]);
        }
    }

    /**
     * @Route("/articlesAjaxPaginate", name="articlesAjaxPaginate")
     */
    public function articlesAjaxPaginate(\Symfony\Component\HttpFoundation\Request $request,PaginatorInterface $paginator)
    {

        $em = $this->getDoctrine()->getManager();

        $paginationArticle = Service\ArticleManager::getPaginateArticles(
            $em,
            $request->get('page', 1),
            $paginator);

        if(count($paginationArticle) == 0){
            return $this->render('Exception/error404.html.twig', [

                'message_error'=>'Сторінка не знайдена'
            ]);
        }

            return $this->render('article/articles.html.twig', [
                'articles' => $paginationArticle,
                'ajax' => true
            ]);

    }
    /**
     * @Route("/page/{alis}", name="showPage", methods={"GET"})
     */
    public function showPage(Request $request, ObjectManager $manager)
    {
        $page = $manager->getRepository(Page::class)->findOneBy(['alisEn'=>$request->get('alis')]);
        if($page == null){
            return $this->render('Exception/error404.html.twig', [
                'advertisement'=>null,
                'message_error'=>'Сторінка не знайдена',
                'condition'=>false
            ]);
        }

        return $this->render('article/page.html.twig', [
            'menu'=>Service\Helper::createMenu($manager),
            'advertisement'=>null,
            'page' => $page,
        ]);
    }

    /**
     * @Route("/article/{id}", name="article", methods={"GET","HEAD","POST"}, requirements={"id":"\d+"})
     */
    public function showArticle(\Symfony\Component\HttpFoundation\Request $request,PaginatorInterface $paginator, ObjectManager $manager)
    {

        $entityManager =  $this->getDoctrine()->getManager();

        $advertisements = $entityManager
            ->getRepository(Advertisement::class)
            ->findAll();

        $article = $entityManager->getRepository(Article::class)->find($request->get('id'));

        $paginationComment =  Service\ArticleManager::getPaginateCommentsForArticle(
            $entityManager,
            $request->get('id'),
            $request->get('page', 1),$paginator);

        if($article == null){
            return $this->render('Exception/error404.html.twig', [
                'advertisement'=>$advertisements,
                'condition'=>false,
                'message_error'=>'Сторінка не знайдена'
            ]);
        }
        $comment = new Comment();

        $formComment = $this->createForm(CommentType::class,$comment);

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
            'ajax'=>false,
            'menu'=>Helper::createMenu($manager),
            'formComment'=>$formComment->createView(),
        ]);


    }
}
