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

        $code = new JournalCode();
        $code->setKeyP(Helper::createAlias('Ємець Петро Андрійович_'.Helper::generatePassword(30)));
        $manager->persist($code);
        $teacher1= new JournalTeacher();
        $teacher1->setName('Ємець Петро Андрійович');
        $teacher1->setCode($code);
        $manager->persist($teacher1);


        $code = new JournalCode();
        $code->setKeyP(Helper::createAlias('Данилова Віталіна Анатоліївна_'.Helper::generatePassword(30)));
        $manager->persist($code);
        $teacher2= new JournalTeacher();
        $teacher2->setName('Данилова Віталіна Анатоліївна');
        $teacher2->setCode($code);
        $manager->persist($teacher2);

        $code = new JournalCode();
        $code->setKeyP(Helper::createAlias('Зуйкова Олена Вікторівна_'.Helper::generatePassword(30)));
        $manager->persist($code);
        $teacher3= new JournalTeacher();
        $teacher3->setName('Зуйкова Олена Вікторівна');
        $teacher3->setCode($code);
        $manager->persist($teacher3);

        $code = new JournalCode();
        $code->setKeyP(Helper::createAlias('Мудрицький Артур Вікотрович_'.Helper::generatePassword(30)));
        $manager->persist($code);
        $teacher4= new JournalTeacher();
        $teacher4->setName('Мудрицький Артур Вікотрович');
        $teacher4->setCode($code);
        $manager->persist($teacher4);

        $code = new JournalCode();
        $code->setKeyP(Helper::createAlias('Нехай Валентин Валентинович_'.Helper::generatePassword(30)));
        $manager->persist($code);
        $teacher5= new JournalTeacher();
        $teacher5->setName('Нехай Валентин Валентинович');
        $teacher5->setCode($code);
        $manager->persist($teacher5);

        $code = new JournalCode();
        $code->setKeyP(Helper::createAlias('Крисько Тетяна Олександрівна_'.Helper::generatePassword(30)));
        $manager->persist($code);
        $teacher6= new JournalTeacher();
        $teacher6->setName('Крисько Тетяна Олександрівна');
        $teacher6->setCode($code);
        $manager->persist($teacher6);

        $code = new JournalCode();
        $code->setKeyP(Helper::createAlias('Любенко Андрій Андрійович_'.Helper::generatePassword(30)));
        $manager->persist($code);
        $teacher7= new JournalTeacher();
        $teacher7->setName('Любенко Андрій Андрійович');
        $teacher7->setCode($code);
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
        $typeMark1->setColor('#ffffff');
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

        $advertisement = new Advertisement();
        $advertisement->setTitle('Оголошення!');
        $advertisement->setDescription('Графік звітів циклових комісій у ІІ семестрі 2018-2019 н.р. та бланки звітів циклової комісії та викладача можна переглянути у розділі "Викладачам - Інформаційно-методичний вісник»');
        $manager->persist($advertisement);

        $advertisement = new Advertisement();
        $advertisement->setTitle('Оголошення!');
        $advertisement->setDescription('План заходів до Міжнародного дня слов\'янської писемності та культуриможна переглянути в розділі "Викладачам - Плани"');
        $manager->persist($advertisement);

        $advertisement = new Advertisement();
        $advertisement->setTitle('Оголошення!');
        $advertisement->setDescription('З витягом із ліцензійних умов провадження освітньої діяльності (для заповнення бланку рейтингу) комісій у навчально-методичний кабінет можна ознайомитися у розділі «Викладачам - Інформаційно-методичний вісник»');
        $manager->persist($advertisement);

        $advertisement = new Advertisement();
        $advertisement->setTitle('До уваги викладачів!');
        $advertisement->setDescription('План роботи коледжу на травень 2019 року можна переглянути у розділі "Викладачам - Плани"');
        $manager->persist($advertisement);

        $advertisement = new Advertisement();
        $advertisement->setTitle('Оголошення!');
        $advertisement->setDescription('Рейтинг успішності студентів 4-го курсу спеціальностей ЕУ,УТ,АД за результатами 7-го семестру навчання (2018-2019 н.р.) можна переглянути в розділі "Навчання - Стипендіальне забезпечення - Рейтинг успішності"');
        $manager->persist($advertisement);


        $manager->flush();
    }
}
