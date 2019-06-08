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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class JournalStudentController extends AbstractController
{
    /**
     * @Route("/journal/group/{group_alis}/students", name="journal_student_list")
     * @Security("is_granted('ROLE_ADMIN')")
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
        $students = $manager->getRepository(JournalStudent::class)->createQueryBuilder('s')
            ->Join('s.group','sg')
            ->where('sg.id = :group_id')
            ->setParameter('group_id',$request->get('group_id'))
            ->orderBy('s.name','asc')
            ->getQuery()
            ->execute();

        $string = '';
        foreach ($students as $student){
            $string.= $this->render('journal/show-humans.html.twig', [
                'human'=>$student,
                'controller_name' => 'listStudentTable',
            ]);
        }

        return new Response($string);
    }

    /**
     * @Route("/journal/ajax/addStudent", name="addStudent")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function addStudent(Request $request, ObjectManager $manager)
    {
        $group = $manager->getRepository(JournalGroup::class)->find($request->get('group_id'));
        if(!$group){
            return new JsonResponse(array('type' => 'error','message'=>'Така група не інуснує.'));
        }

        $code = new JournalCode();
        $code->setKeyP(Helper::createAlias($request->get('student_name')).'_'.Helper::generatePassword(30));
        $code->setRole('ROLE_STUDENT');

        $manager->persist($code);

        $student = new JournalStudent();
        $student->setName(trim($request->get('student_name')));
        $student->setGroup($group);
        $student->setCode($code);


        $manager->persist($student);

        $manager->flush();

        return new JsonResponse(array('type' => 'info','message'=>'Судента додано.'));

    }

    /**
     * @Route("/journal/ajax/updateStudent", name="updateStudent")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function updateStudent(Request $request, ObjectManager $manager)
    {


        $student = $manager->getRepository(JournalStudent::class)->find($request->get('student_id'));
        if(!$student){
            return new JsonResponse(array('type' => 'error','message'=>'Судента не знайдено.'));
        }
        $student->setName(trim($request->get('student_name')));
        $manager->persist($student);

        if(!$student->getCode()){
            $code = new JournalCode();
            $code->setKeyP(trim($request->get('key')));
            $manager->persist($code);
            $student->setCode($code);
        }else{
            $code = $manager->getRepository(JournalCode::class)->find($student->getCode()->getId());
            $code->setKeyP(trim($request->get('key')));
            $manager->persist($code);
        }

        $manager->flush();

        return new JsonResponse(array('type' => 'info','message'=>'Судента змінено.'));
    }

    /**
     * @Route("/journal/ajax/deleteStudent", name="deleteStudent")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function deleteStudent(Request $request, ObjectManager $manager)
    {

        $student = $manager->getRepository(JournalStudent::class)->find($request->get('student_id'));
        if(!$student){
            return new JsonResponse(array('type' => 'error','message'=>'Судента не знайдено.'));
        }
        if($student->getCode()){
            $code = $manager->getRepository(JournalCode::class)->find($student->getCode()->getId());
            $user = $code->getUser();

            if($user){
                $user->removeRole('ROLE_STUDENT');
            }
            $manager->remove($code);
        }

        $manager->remove($student);
        $manager->flush();

        return new JsonResponse(array('type' => 'info','message'=>'Судента видалено.'));

    }
}
