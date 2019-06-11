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
use App\Entity\User;
use App\Service\Helper;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use phpDocumentor\Reflection\Types\Integer;
//php bin/console doctrine:fixtures:load

class AppFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {

        $generateName =  Helper::generateName();

        $arrayTeacher = self::JournalTeacherCode($manager,$generateName);

       $arrayGroup = self::JournalGroup($manager,$arrayTeacher);

        self::JournalStudentCode($manager,$arrayGroup[0]);

        self::JournalTypeMark($manager);

        self::JournalTypeFormControl($manager);

        self::loadJournalGradingSystem($manager);

        self::loadAdvertisement($manager);

        self::loadUser($manager);

        $manager->flush();
    }

    private function JournalTeacherCode(ObjectManager &$manager,$generateName){
        $arrayTeacher = [];

        $code = new JournalCode();
        $code->setKeyP(Helper::createAlias('Ємець Петро Андрійович_'.Helper::generatePassword(30)));
        $code->setRole('ROLE_TEACHER');
        $manager->persist($code);
        $teacher= new JournalTeacher();
        $teacher->setName('Ємець Петро Андрійович');
        $teacher->setCode($code);
        $manager->persist($teacher);
        $arrayTeacher[] = $teacher;

        $code = new JournalCode();
        $code->setKeyP(Helper::createAlias('Данилова Віталіна Анатоліївна_'.Helper::generatePassword(30)));
        $code->setRole('ROLE_TEACHER');
        $manager->persist($code);
        $teacher= new JournalTeacher();
        $teacher->setName('Данилова Віталіна Анатоліївна');
        $teacher->setCode($code);
        $manager->persist($teacher);
        $arrayTeacher[] = $teacher;

        $code = new JournalCode();
        $code->setKeyP(Helper::createAlias('Зуйкова Олена Вікторівна_'.Helper::generatePassword(30)));
        $code->setRole('ROLE_TEACHER');
        $manager->persist($code);
        $teacher= new JournalTeacher();
        $teacher->setName('Зуйкова Олена Вікторівна');
        $teacher->setCode($code);
        $manager->persist($teacher);
        $arrayTeacher[] = $teacher;

        $code = new JournalCode();
        $code->setKeyP(Helper::createAlias('Мудрицький Артур Вікотрович_'.Helper::generatePassword(30)));
        $code->setRole('ROLE_TEACHER');
        $manager->persist($code);
        $teacher= new JournalTeacher();
        $teacher->setName('Мудрицький Артур Вікотрович');
        $teacher->setCode($code);
        $manager->persist($teacher);
        $arrayTeacher[] = $teacher;

        $code = new JournalCode();
        $code->setKeyP(Helper::createAlias('Нехай Валентин Валентинович_'.Helper::generatePassword(30)));
        $code->setRole('ROLE_TEACHER');
        $manager->persist($code);
        $teacher= new JournalTeacher();
        $teacher->setName('Нехай Валентин Валентинович');
        $teacher->setCode($code);
        $manager->persist($teacher);
        $arrayTeacher[] = $teacher;

        $code = new JournalCode();
        $code->setKeyP(Helper::createAlias('Крисько Тетяна Олександрівна_'.Helper::generatePassword(30)));
        $code->setRole('ROLE_TEACHER');
        $manager->persist($code);
        $teacher= new JournalTeacher();
        $teacher->setName('Крисько Тетяна Олександрівна');
        $teacher->setCode($code);
        $manager->persist($teacher);
        $arrayTeacher[] = $teacher;

        $code = new JournalCode();
        $code->setKeyP(Helper::createAlias('Любенко Андрій Андрійович_'.Helper::generatePassword(30)));
        $code->setRole('ROLE_TEACHER');
        $manager->persist($code);
        $teacher= new JournalTeacher();
        $teacher->setName('Любенко Андрій Андрійович');
        $teacher->setCode($code);
        $manager->persist($teacher);
        $arrayTeacher[] = $teacher;

        for ($i=0;$i<20;$i++){
            $code = new JournalCode();
            $randomIndex = rand(0,count($generateName));
            $name = $generateName[$randomIndex];
            unset($generateName[$randomIndex]);
            sort($generateName);
            $code->setKeyP(Helper::createAlias($name.'_'.Helper::generatePassword(30)));
            $code->setRole('ROLE_TEACHER');
            $manager->persist($code);
            $teacher= new JournalTeacher();
            $teacher->setName($name);
            $teacher->setCode($code);
            $manager->persist($teacher);
            $arrayTeacher[] = $teacher;
        }

        return $arrayTeacher;
    }

    private function JournalGroup(ObjectManager &$manager,&$arrayTeacher){
        $i=0;
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

        $specialty4 = new JournalSpecialty();
        $specialty4->setDescription('Это УТ');
        $specialty4->setName('УТ');
        $manager->persist($specialty4);

        $specialty4 = new JournalSpecialty();
        $specialty4->setDescription('Это ТТ');
        $specialty4->setName('ТТ');
        $manager->persist($specialty4);
        $arrayGroup = [];
        $group1 = new JournalGroup();
        $group1->setName('ПС-1501');
        $group1->setDescription('Это група пс');
        $group1->setCurator($arrayTeacher[++$i]);
        $group1->setSpecialty($specialty1);
        $manager->persist($group1);
        $arrayGroup[] = $group1;

        $group2 = new JournalGroup();
        $group2->setName('КС-1502');
        $group2->setDescription('Это група КС');
        $group2->setCurator($arrayTeacher[++$i]);
        $group2->setSpecialty($specialty2);
        $manager->persist($group2);
        $arrayGroup[] = $group2;

        $group3 = new JournalGroup();
        $group3->setName('АД-1502');
        $group3->setDescription('Это група АД');
        $group3->setCurator($arrayTeacher[++$i]);
        $group3->setSpecialty($specialty3);
        $manager->persist($group3);
        $arrayGroup[] = $group3;

        $group4 = new JournalGroup();
        $group4->setName('ПС-1401');
        $group4->setDescription('Это група пс');
        $group4->setCurator($arrayTeacher[++$i]);
        $group4->setSpecialty($specialty1);
        $manager->persist($group4);
        $arrayGroup[] = $group4;

        return $arrayGroup;
    }

    private function JournalStudentCode(ObjectManager &$manager,&$group1){
        $code = new JournalCode();
        $code->setKeyP(Helper::createAlias('Аврамішин Іван Сергійович').'_'.Helper::generatePassword(30));
        $code->setRole('ROLE_STUDENT');
        $manager->persist($code);

        $student = new JournalStudent();
        $student->setName('Аврамішин Іван Сергійович');
        $student->setGroup($group1);
        $student->setCode($code);
        $manager->persist($student);

        $code = new JournalCode();
        $code->setKeyP(Helper::createAlias('Брей Ігор Володимирович').'_'.Helper::generatePassword(30));
        $code->setRole('ROLE_STUDENT');
        $manager->persist($code);

        $student = new JournalStudent();
        $student->setName('Брей Ігор Володимирович');
        $student->setGroup($group1);
        $student->setCode($code);
        $manager->persist($student);

        $code = new JournalCode();
        $code->setKeyP(Helper::createAlias('Бузинов Владислав Михайлович').'_'.Helper::generatePassword(30));
        $code->setRole('ROLE_STUDENT');
        $manager->persist($code);

        $student = new JournalStudent();
        $student->setName('Бузинов Владислав Михайлович');
        $student->setGroup($group1);
        $student->setCode($code);
        $manager->persist($student);

        $code = new JournalCode();
        $code->setKeyP(Helper::createAlias('Дударенко Володимер Володимирович').'_'.Helper::generatePassword(30));
        $code->setRole('ROLE_STUDENT');
        $manager->persist($code);

        $student = new JournalStudent();
        $student->setName('Дударенко Володимер Володимирович');
        $student->setGroup($group1);
        $student->setCode($code);
        $manager->persist($student);

        $code = new JournalCode();
        $code->setKeyP(Helper::createAlias('Завгородня Поліна Сергіївна').'_'.Helper::generatePassword(30));
        $code->setRole('ROLE_STUDENT');
        $manager->persist($code);

        $student = new JournalStudent();
        $student->setName('Завгородня Поліна Сергіївна');
        $student->setGroup($group1);
        $student->setCode($code);
        $manager->persist($student);

        $code = new JournalCode();
        $code->setKeyP(Helper::createAlias('Івасюк Данило Валерійович').'_'.Helper::generatePassword(30));
        $code->setRole('ROLE_STUDENT');
        $manager->persist($code);

        $student = new JournalStudent();
        $student->setName('Івасюк Данило Валерійович');
        $student->setGroup($group1);
        $student->setCode($code);
        $manager->persist($student);

        $code = new JournalCode();
        $code->setKeyP(Helper::createAlias('Криієнко Богдан Олександрович').'_'.Helper::generatePassword(30));
        $code->setRole('ROLE_STUDENT');
        $manager->persist($code);

        $student = new JournalStudent();
        $student->setName('Криієнко Богдан Олександрович');
        $student->setGroup($group1);
        $student->setCode($code);
        $manager->persist($student);

        $code = new JournalCode();
        $code->setKeyP(Helper::createAlias('Матвієнко Валерія Іорівна').'_'.Helper::generatePassword(30));
        $code->setRole('ROLE_STUDENT');
        $manager->persist($code);

        $student = new JournalStudent();
        $student->setName('Матвієнко Валерія Іорівна');
        $student->setGroup($group1);
        $student->setCode($code);
        $manager->persist($student);
    }

    private function JournalTypeMark(ObjectManager &$manager){
        $typeMark1=  new JournalTypeMark();
        $typeMark1->setName('Оцінка');
        $typeMark1->setColor('#ffffff');
        $manager->persist($typeMark1);

        $typeMark1=  new JournalTypeMark();
        $typeMark1->setName('Атестація');
        $typeMark1->setColor('#8a9eff');
        $typeMark1->setAverage(1);
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
    }

    private function JournalTypeFormControl(ObjectManager &$manager){
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
    }

    private function loadJournalGradingSystem(ObjectManager &$manager){
        $system = new JournalGradingSystem();
        $system->setSystem('5');
        $manager->persist($system);

        $system = new JournalGradingSystem();
        $system->setSystem('12');
        $manager->persist($system);
    }

    private function loadAdvertisement(ObjectManager &$manager){
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
    }

    private function loadUser(ObjectManager &$manager){

        $user = new User();
        $user->setPassword('$2y$13$0XQoNXzgLsp/NaxSJp.9meKhVYJfWgVJCIcwjPzWyAZRIdjWWz/pW');
        $user->setEmail('admin@admin.admin');
        $user->setUsername('admin');
        $user->addRole('ROLE_ADMIN');
        $user->setEnabled(1);
        $manager->persist($user);

        $user = new User();
        $user->setPassword('$2y$13$0XQoNXzgLsp/NaxSJp.9meKhVYJfWgVJCIcwjPzWyAZRIdjWWz/pW');
        $user->setEmail('user@user.user');
        $user->setUsername('user');
        $user->addRole('ROLE_USER');
        $user->setEnabled(1);
        $manager->persist($user);

        $user = new User();
        $user->setPassword('$2y$13$0XQoNXzgLsp/NaxSJp.9meKhVYJfWgVJCIcwjPzWyAZRIdjWWz/pW');
        $user->setEmail('teacher@teacher.teacher');
        $user->setUsername('teacher');
        $user->addRole('ROLE_TEACHER');
        $user->setEnabled(1);
        $manager->persist($user);

        $user = new User();
        $user->setPassword('$2y$13$0XQoNXzgLsp/NaxSJp.9meKhVYJfWgVJCIcwjPzWyAZRIdjWWz/pW');
        $user->setEmail('student@student.student');
        $user->setUsername('student');
        $user->addRole('ROLE_TEACHER');
        $user->setEnabled(1);
        $manager->persist($user);


    }


}
