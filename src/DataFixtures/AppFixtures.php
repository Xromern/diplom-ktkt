<?php

namespace App\DataFixtures;

use App\Entity\Advertisement;
use App\Entity\Article;
use App\Entity\CategoryArticle;
use App\Entity\JournalCode;
use App\Entity\JournalGradingSystem;
use App\Entity\JournalGroup;
use App\Entity\JournalSpecialty;
use App\Entity\JournalStudent;
use App\Entity\JournalTeacher;
use App\Entity\JournalTypeFormControl;
use App\Entity\JournalTypeMark;
use App\Service\Helper;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use phpDocumentor\Reflection\Types\Integer;
//php bin/console doctrine:fixtures:load

class AppFixtures extends Fixture
{
    public function loadk(ObjectManager $manager)
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

    public function load(ObjectManager $manager)
    {

        $specialty1 = new JournalSpecialty();
        $specialty1->setDescription('Это ПС');
        $specialty1->setName('ПС');
        $manager->persist($specialty1);

        $specialty2 = new JournalSpecialty();
        $specialty2->setDescription('Это КС');
        $specialty2->setName('КС');
        $manager->persist($specialty2);

        $specialty3 = new JournalSpecialty();
        $specialty3->setDescription('Это АД');
        $specialty3->setName('АД');
        $manager->persist($specialty3);

        $specialty4 = new JournalSpecialty();
        $specialty4->setDescription('Это ЕП');
        $specialty4->setName('ЕП');
        $manager->persist($specialty4);

        $teacher1 = new JournalTeacher();
        $teacher1->setName('Ємець Петро Андрійович');
        $manager->persist($teacher1);

        $teacher2 = new JournalTeacher();
        $teacher2->setName('Данилова Віталіна Анатоліївна');
        $manager->persist($teacher2);

        $teacher3= new JournalTeacher();
        $teacher3->setName('Зуйкова Олена Вікторівна');
        $manager->persist($teacher3);

        $teacher4 = new JournalTeacher();
        $teacher4->setName('Мудрицький Артур Вікотрович');
        $manager->persist($teacher4);

        $teacher5 = new JournalTeacher();
        $teacher5->setName('Нехай Валентин Валентинович');
        $manager->persist($teacher5);

        $teacher6 = new JournalTeacher();
        $teacher6->setName('Крисько Тетяна Олександрівна');
        $manager->persist($teacher6);

        $teacher7 = new JournalTeacher();
        $teacher7->setName('Любенко Андрій Андрійович');
        $manager->persist($teacher7);

        $group1 = new JournalGroup();
        $group1->setName('ПС-1501');
        $group1->setDescription('Это група пс');
        $group1->setCurator($teacher2);
        $group1->setSpecialty($specialty1);
        $manager->persist($group1);

        $group2 = new JournalGroup();
        $group2->setName('КС-1502');
        $group2->setDescription('Это група КС');
        $group2->setCurator($teacher1);
        $group2->setSpecialty($specialty2);
        $manager->persist($group2);

        $group3 = new JournalGroup();
        $group3->setName('АД-1502');
        $group3->setDescription('Это група АД');
        $group3->setCurator($teacher3);
        $group3->setSpecialty($specialty3);
        $manager->persist($group3);

        $group4 = new JournalGroup();
        $group4->setName('ПС-1401');
        $group4->setDescription('Это група пс');
        $group4->setCurator($teacher4);
        $group4->setSpecialty($specialty1);
        $manager->persist($group4);

        $code = new JournalCode();
        $code->setKeyP(Helper::createAlias('Аврамішин Іван Сергійович').'_'.Helper::generatePassword(30));
        $manager->persist($code);

        $student = new JournalStudent();
        $student->setName('Аврамішин Іван Сергійович');
        $student->setGroup($group1);
        $student->setCode($code);
        $manager->persist($student);

        $code = new JournalCode();
        $code->setKeyP(Helper::createAlias('Брей Ігор Володимирович').'_'.Helper::generatePassword(30));
        $manager->persist($code);

        $student = new JournalStudent();
        $student->setName('Брей Ігор Володимирович');
        $student->setGroup($group1);
        $student->setCode($code);
        $manager->persist($student);

        $code = new JournalCode();
        $code->setKeyP(Helper::createAlias('Бузинов Владислав Михайлович').'_'.Helper::generatePassword(30));
        $manager->persist($code);

        $student = new JournalStudent();
        $student->setName('Бузинов Владислав Михайлович');
        $student->setGroup($group1);
        $student->setCode($code);
        $manager->persist($student);

        $code = new JournalCode();
        $code->setKeyP(Helper::createAlias('Дударенко Володимер Володимирович').'_'.Helper::generatePassword(30));
        $manager->persist($code);

        $student = new JournalStudent();
        $student->setName('Дударенко Володимер Володимирович');
        $student->setGroup($group1);
        $student->setCode($code);
        $manager->persist($student);

        $code = new JournalCode();
        $code->setKeyP(Helper::createAlias('Завгородня Поліна Сергіївна').'_'.Helper::generatePassword(30));
        $manager->persist($code);

        $student = new JournalStudent();
        $student->setName('Завгородня Поліна Сергіївна');
        $student->setGroup($group1);
        $student->setCode($code);
        $manager->persist($student);

        $code = new JournalCode();
        $code->setKeyP(Helper::createAlias('Івасюк Данило Валерійович').'_'.Helper::generatePassword(30));
        $manager->persist($code);

        $student = new JournalStudent();
        $student->setName('Івасюк Данило Валерійович');
        $student->setGroup($group1);
        $student->setCode($code);
        $manager->persist($student);

        $code = new JournalCode();
        $code->setKeyP(Helper::createAlias('Криієнко Богдан Олександрович').'_'.Helper::generatePassword(30));
        $manager->persist($code);

        $student = new JournalStudent();
        $student->setName('Криієнко Богдан Олександрович');
        $student->setGroup($group1);
        $student->setCode($code);
        $manager->persist($student);

        $code = new JournalCode();
        $code->setKeyP(Helper::createAlias('Матвієнко Валерія Іорівна').'_'.Helper::generatePassword(30));
        $manager->persist($code);

        $student = new JournalStudent();
        $student->setName('Матвієнко Валерія Іорівна');
        $student->setGroup($group1);
        $student->setCode($code);
        $manager->persist($student);

        $typeMark1=  new JournalTypeMark();
        $typeMark1->setName('Оцінка');
        $typeMark1->setColor('none');
        $manager->persist($typeMark1);

        $typeMark1=  new JournalTypeMark();
        $typeMark1->setName('Атестація');
        $typeMark1->setColor('#00008B');
        $manager->persist($typeMark1);

        $typeMark1=  new JournalTypeMark();
        $typeMark1->setName('Контрольна');
        $typeMark1->setColor('#FD5E53');
        $manager->persist($typeMark1);

        $typeMark1=  new JournalTypeMark();
        $typeMark1->setName('Самостійна');
        $typeMark1->setColor('#7FFFD4');
        $manager->persist($typeMark1);

        $typeMark1=  new JournalTypeMark();
        $typeMark1->setName('Лабораторна');
        $typeMark1->setColor('#006633');
        $manager->persist($typeMark1);


        $typeJournal = new JournalTypeFormControl();
        $typeJournal->setName('Журнал');
        $manager->persist($typeJournal);

        $typeJournal = new JournalTypeFormControl();
        $typeJournal->setName('Диплом');
        $manager->persist($typeJournal);

        $typeJournal = new JournalTypeFormControl();
        $typeJournal->setName('Курсова');
        $manager->persist($typeJournal);
        $typeJournal = new JournalTypeFormControl();

        $typeJournal->setName('Практика');
        $manager->persist($typeJournal);

        $system = new JournalGradingSystem();
        $system->setSystem('5');
        $manager->persist($system);

        $system = new JournalGradingSystem();
        $system->setSystem('12');
        $manager->persist($system);

        $manager->flush();
    }
}
