<?php

namespace App\DataFixtures;

use App\Entity\Advertisement;
use App\Entity\Article;
use App\Entity\CategoryArticle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use phpDocumentor\Reflection\Types\Integer;
//php bin/console doctrine:fixtures:load

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        /*$category = [];
        for($i=0;$i<5;$i++){

            $category[] = (new CategoryArticle())->setTitle('category');
            $manager->persist($category[$i]);

        }*/
        for($i = 0;$i<8;$i++ ) {
            $advertisement= new Advertisement();
            $advertisement->setTitle('title test '.$i);
            $advertisement->setDescription('description test '.$i);
            $manager->persist($advertisement);
        }
        for($i = 0;$i<67;$i++ ) {
            $article = new Article();
            $article->setBody('body test: '.$i);
            $article->setDescription('description test: '.$i);
            $article->setTitle('title test: '.$i);
            $article->setImage('ktkt.png');

            $randStart = rand(0,5);
            $randEnd = rand(0,5);

          /*  for($i = $randStart;$i<$randEnd;$i++){
                $article->setCategories($category[$i]);
            }*/

            $manager->persist($article);
        }
        //

        $manager->flush();
    }
}
