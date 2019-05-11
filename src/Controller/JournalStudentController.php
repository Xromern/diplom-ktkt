<?php

namespace App\Controller;

use App\Entity\JournalCode;
use App\Entity\JournalGroup;
use App\Entity\JournalStudent;
use App\Service\Helper;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JournalStudentController extends AbstractController
{
    /**
     * @Route("/journal/group/{group_alis}/students", name="journal_student_list")
     */
    public function listStudent(Request $request, ObjectManager $manager)
    {
        $journalGroup = $manager->getRepository(JournalGroup::class)->getGroupByAlis($request->get('group_alis'));

        if(!$journalGroup){
            return $this->render('journal/Exception/error404.html.twig',['message_error'=>'Така група не інуснує.']);
        }

        return $this->render('journal/journal_student/list-student.html.twig', [
            'group'=>$journalGroup,
            'controller_name' => 'JournalStudentController',
        ]);
    }

    /**
     * @Route("/journal/ajax/showStudent", name="showStudent")
     */
    public function listStudentTable(Request $request, ObjectManager $manager)
    {
        $group = $manager->getRepository(JournalGroup::class)->find($request->get('group_id'));
        $string = '';
        foreach ($group->getStudents() as $student){
            $string.= $this->render('journal/show-humans.html.twig', [
                'human'=>$student,
                'controller_name' => 'listStudentTable',
            ]);
        }
        return new Response($string);
    }

    /**
     * @Route("/journal/ajax/addStudent", name="addStudent")
     */
    public function addStudent(Request $request, ObjectManager $manager)
    {
        $group = $manager->getRepository(JournalGroup::class)->find(29);
        if(!$group){
            return new JsonResponse(array('type' => 'error','message'=>'Така група не інуснує.'));
        }



        $code = new JournalCode();
        $code->setKey(Helper::createAlias($request->get('student_name').'_'.random_bytes(22)));
     //   $code->setStudent($student);
        $manager->persist($code);

        $student = new JournalStudent();
        $student->setName('43');
        $student->setCode($code);
        $manager->persist($student);


       // $group->setStudents($student);
      //  $manager->persist($group);

        $manager->flush();

        return new JsonResponse(array('type' => 'info','message'=>'Судента додано.'));

    }

}
