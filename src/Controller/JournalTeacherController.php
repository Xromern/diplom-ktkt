<?php

namespace App\Controller;

use App\Entity\JournalCode;
use App\Entity\JournalGroup;
use App\Entity\JournalSubject;
use App\Entity\JournalTeacher;
use App\Entity\User;
use App\Service\Helper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request as Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class JournalTeacherController extends AbstractController
{

    /**
     * @Route("/journal/teachers", name="journal_teachers_list")
     */
    public function listStudent(Request $request, ObjectManager $manager)
    {
        $journalTeachers = $manager->getRepository(JournalTeacher::class)->findAll();

        return $this->render('journal/journal_teacher/list-teacher.html.twig', [
            'teachers'        => $journalTeachers,
            'controller_name' => 'listStudent',
            'menu'=>Helper::createMenu($manager),
        ]);
    }

    /**
     * @Route("/journal/ajax/showTeacher", name="showTeacher")
     */
    public function listTeacherTable(Request $request, ObjectManager $manager)
    {
        $journalTeachers = $manager->getRepository(JournalTeacher::class)->createQueryBuilder('t')
            ->orderBy('t.name','asc')
            ->getQuery()
            ->execute();

        $string = '';
        foreach ($journalTeachers as $teacher){
            $string.= $this->render('journal/show-humans.html.twig', [
                'human'=>$teacher,
                'controller_name' => 'listTeacherTable',
            ]);
        }
        return new Response($string);
    }
    /**
     * @Route("/journal/ajax/addTeacher", name="addTeacher")
     */
    public function addTeacher(Request $request, ObjectManager $manager)
    {

        $code = new JournalCode();
        $code->setKeyP(Helper::createAlias($request->get('teacher_name')).'_'.Helper::generatePassword(30));
        $code->setRole('ROLE_TEACHER');
        $manager->persist($code);
        $user = new User();
        $teacher= new JournalTeacher();
        $teacher->setName($request->get('teacher_name'));
        $teacher->setCode($code);
        $manager->persist($teacher);

        $manager->flush();

        return new JsonResponse(array('type' => 'info','message'=>'Вчителя додано.'));

    }

    /**
     * @Route("/journal/ajax/updateTeacher", name="updateTeacher")
     */
    public function updateTeacher(Request $request, ObjectManager $manager)
    {
        $teacher = $manager->getRepository(JournalTeacher::class)->find($request->get('teacher_id'));
        if(!$teacher){
            return new JsonResponse(array('type' => 'error','message'=>'Вчителя не знайдено.'));
        }

        $teacher->setName($request->get('teacher_name'));
        $manager->persist($teacher);

        if(!$teacher->getCode()){
            $code = new JournalCode();
            $code->setKeyP($request->get('key'));
            $manager->persist($code);
            $teacher->setCode($code);
        }else{
            $code = $manager->getRepository(JournalCode::class)->find($teacher->getCode()->getId());
            $code->setKeyP($request->get('key'));
            $manager->persist($code);
        }

        $manager->flush();

        return new JsonResponse(array('type' => 'info','message'=>'Вчителя змінено.'));
    }

    /**
     * @Route("/journal/ajax/deleteTeacher", name="deleteTeacher")
     */
    public function deleteTeacher(Request $request, ObjectManager $manager)
    {

        $teacher = $manager->getRepository(JournalTeacher::class)->find($request->get('teacher_id'));
        if(!$teacher){
            return new JsonResponse(array('type' => 'error','message'=>'Вчителя не знайдено.'));
        }
        $manager->remove($teacher);
        if($teacher->getCode()){
            $code = $manager->getRepository(JournalCode::class)->find($teacher->getCode()->getId());
            $user = $code->getUser();

            if($user){
                $user->removeRole('ROLE_TEACHER');
            }

            $manager->remove($code);
        }

        $manager->flush();

        return new JsonResponse(array('type' => 'info','message'=>'Вчителя видалено.'));

    }

    /**
     * @Route("/journal/ajax/curatorSelect", name="journal_curator_select")
     */
    public function curatorSelect(Request $request, ObjectManager $manager)
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }

        $teacher = $manager->getRepository(JournalTeacher::class)->createQueryBuilder('t')
            ->leftJoin('t.group', 'tg')
            ->andWhere('tg IS NULL');
        $curator_id = null;

        if(($request->get('group_alis'))) {

            $curator = $manager->getRepository(JournalGroup::class)
                ->getGroupByAlis($request->get('group_alis'));

            if($curator->getCurator()) {
                $curator_id = $curator->getCurator()->getId();
                $teacher = $teacher->orWhere('t.id = :curator')
                    ->setParameter('curator', $curator_id);
            }
        }

        $teacher =  $teacher->getQuery()->execute();

        $string = "";
        $string.="<option value='0'></option>";
        foreach ($teacher as $item){
            if($item->getId() == $curator_id){
                $selected = "selected";
            }else{
                $selected = "";
            }
            $string.="<option $selected value='".$item->getId()."'>".$item->getName()."</option>";
        }

        return new Response($string);
    }



    /**
     * @Route("/journal/ajax/teacherSelect", name="teacherSelect")
     */
    public function teacherSelect(Request $request, ObjectManager $manager){
        $subject = $manager->getRepository(JournalSubject::class)->find($request->get('subject_id',0));
        if($subject){
            $mainTeacher = $subject->getMainTeacher();
        }else{
            $mainTeacher = null;
        }

        $teachers = $manager->getRepository(JournalTeacher::class)->findAll();

        $string="<option value='0'></option>";
        foreach ($teachers as $item){
            if($mainTeacher != null) $teacherId = $mainTeacher->getId(); else $teacherId = 0;
            if($item->getId() == $teacherId){
                $selected = "selected";
            }else{
                $selected = "";
            }
            $string.="<option $selected value='".$item->getId()."'>".$item->getName()."</option>";
        }
        return new JsonResponse(array('teachers'=>$string));

    }
}
