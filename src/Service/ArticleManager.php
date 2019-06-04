<?php


namespace App\Service;


use App\Entity\Advertisement;
use App\Entity\Article;
use App\Entity\Comment;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class ArticleManager
{
    /**
     * Получить кометанрии с пагинацией
     * @var ObjectManager $manager
     * @var int $article_id
     * @var int $page
     * @var PaginatorInterface $paginator
     * @return PaginationInterface
     **/
    public static function getPaginateCommentsForArticle(ObjectManager $manager,int $article_id,int $page,PaginatorInterface $paginator){

        $commentRepo = $manager->getRepository(Comment::class);

        $commentQuery = $commentRepo->createQueryBuilder('c')
            ->Where('c.article = :article_id')
            ->orderBy('c.id', 'desc')
            ->setParameter('article_id',  $article_id)
            ->getQuery();

        $paginationComment = $paginator->paginate(
            $commentQuery,
            $page,
            15
        );

        $paginationComment->setUsedRoute('article');

        $paginationComment->setParam('id', $article_id);



        return $paginationComment;

    }

    /**
     * Получить артикли с пагинацией
     * @var ObjectManager $manager
     * @var int $article_id
     * @var int $page
     * @var PaginatorInterface $paginator
     * @return PaginationInterface
     **/
    public static function getPaginateArticles(ObjectManager $manager, int $page, PaginatorInterface $paginator){

        $articlesRepo = $manager->getRepository(Article::class);

        $articlesQuery = $articlesRepo->createQueryBuilder('a')
            ->orderBy('a.id','DESC')
            ->getQuery();

        $paginationArticle = $paginator->paginate(
            $articlesQuery,
            $page,
            9
        );
        $paginationArticle->setTemplate('pagination/paginate.html.twig');
        $paginationArticle->setUsedRoute('articles');
        $paginationArticle->setParam('page', $page);


        return $paginationArticle;

    }
}