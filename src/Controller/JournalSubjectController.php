<?php

namespace App\Controller;

use App\Entity\JournalDateMark;
use App\Entity\JournalGroup;
use App\Entity\JournalMark;
use App\Entity\JournalStudent;
use App\Entity\JournalSubject;
use App\Entity\JournalTeacher;
use App\Entity\JournalTypeFormControl;
use App\Entity\JournalTypeMark;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service;

class JournalSubjectController extends AbstractController
{
    /**
     * @Route("/journal/subject", name="journal_subject")
     */
    public function index()
    {
        return $this->render('journal_subject/index.html.twig', [
            'controller_name' => 'JournalSubjectController',
        ]);
    }

    /**
     * @Route("/journal/group/{group_alis}", name="journal_show_one_group")
     */
    public function showSubjects(Request $request,ObjectManager $manager)
    {

        //$= $manager->getRepository(JournalTypeFormControl::class)->findAll();

        return $this->render('journal/journal_group/one-group.html.twig',[
            'group'=>$journalGroup,
            'formControl'=>$formControl
        ]);

    }

    /**
     * @Route("/journal/ajax/subjectAdd", name="subjectAdd")
     */
    public function addSubject(Request $request, ObjectManager $manager)
    {
        $listStudent = json_decode($request->get('list_student'));

        $group = $manager->getRepository(JournalGroup::class)->find($request->get('group_id'));//$request->get('group_id'));
        $teacher = $manager->getRepository(JournalTeacher::class)->find($request->get('teacher_id'));//$request->get('teacher_id'));
        $typeMark = $manager->getRepository(JournalTypeMark::class)->find(1);
        $formControl = $manager->getRepository(JournalTypeFormControl::class)->find($request->get('form_control_id'));

        $subject = new JournalSubject();
        $subject->setName($request->get('name_subject'));//$request->get('name_subject'));
        $subject->setDescription('3');
        $subject->setMainTeacher($teacher);
        $subject->setGroup($group);
        $subject->setTypeFormControl($formControl);
        $manager->persist($subject);

        Service\Journal::createJournal($typeMark,$subject,$listStudent,$manager);

        $manager->flush();
        return new Response('3');
    }
}
