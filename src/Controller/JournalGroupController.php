<?php

namespace App\Controller;


use App\Entity\JournalGradingSystem;
use App\Entity\JournalGroup;
use App\Entity\JournalSpecialty;
use App\Entity\JournalSubject;
use App\Entity\JournalTeacher;
use App\Entity\JournalTypeFormControl;
use App\Repository\TeacherRepository;
use App\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request as Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class JournalGroupController extends AbstractController
{
    /**
     * Вивід груп
     * @Route("/journal", name="journal_group")
     */
    public function listGroup(Request $request, ObjectManager $manager)
    {
        if($this->isGranted('ROLE_STUDENT')){
            $student = $this->getUser()->getCode()->getStudent();
            return $this->redirectToRoute('journal_show_one_group',['group_alis'=>$student->getGroup()->getAlisEn()]);
        }

        $specialty = $manager->getRepository(JournalSpecialty::class)->findAll();

        $listGroups = $manager->getRepository(JournalGroup::class)->createQueryBuilder('g')
        ->leftJoin('g.curator','c')
        ->leftJoin('g.subjects','s');
        if($this->isGranted('ROLE_TEACHER')) {
            $currentTeacher = Service\Journal::Teacher($this->getUser());
            $listGroups = $listGroups->leftJoin('g.specialty', 'spec')
                ->leftJoin('s.mainTeacher', 'mt')
                ->andWhere('mt.id = :teacher_id or c.id = :teacher_id')
                ->setParameter('teacher_id', $currentTeacher->getId());
        }
        $listGroups = $listGroups
        ->getQuery()
        ->execute();

        $specialtyList = [];
        foreach ($specialty as $item) {
            $specialtyList[] = ['specialty'=>$item,'groups'=>[]];
        }
        foreach ($specialtyList as &$itemSpec){
            foreach ($listGroups as $itemGroup){
                if($itemSpec['specialty']->getId() == $itemGroup->getSpecialty()->getId())
                array_push($itemSpec['groups'],$itemGroup);
            }
        }

        if ($request->isXmlHttpRequest())
        {
            return $this->render('journal/journal_group/group-block.html.twig', [
                'controller_name' => 'JournalGroupController',
                'specialty'=>$specialtyList
            ]);
        }else{
            return $this->render('journal/journal_group/list-group-specialty.html.twig', [
                'controller_name' => 'JournalGroupController',
                'menu'=>Service\Helper::createMenu($manager),
                'specialty'=>$specialtyList
            ]);
        }
    }

    /**
     * Показати сторінку групи
     * @Route("/journal/group/{group_alis}", name="journal_show_one_group")
     */
    public function showGroup(Request $request,ObjectManager $manager)
    {
        $journalGroup = $manager->getRepository(JournalGroup::class)
            ->getGroupByAlis($request->get('group_alis'));

        $gradingSystem = $manager->getRepository(JournalGradingSystem::class)->findAll();


        $tfc = $manager->getRepository(JournalTypeFormControl::class)->findAll();
        $subjects = $manager->getRepository(JournalSubject::class)->createQueryBuilder('s')
            ->leftJoin('s.mainTeacher','mt')
            ->leftJoin('s.group','g')
            ->leftJoin('g.curator','c')
            ->leftJoin('s.typeFormControl','tfc');
        if($this->isGranted('ROLE_TEACHER')) {

            $currentTeacher = Service\Journal::Teacher($this->getUser());
            $subjects =  $subjects->andWhere('mt.id = :teacher_id or c.id = :teacher_id')
                 ->andWhere('g.id = :group_id')
                 ->setParameter('teacher_id', $currentTeacher->getId())
                 ->setParameter('group_id', $journalGroup->getId());

        }elseif($this->isGranted('ROLE_STUDENT')){

            $currentStudent = Service\Journal::Student($this->getUser());
            $subjects =  $subjects->leftJoin('g.students','stud')
                ->andWhere('stud.id = :student_id')
                ->andWhere('g.id = :group_id')
                ->setParameter('student_id', $currentStudent->getId())
                ->setParameter('group_id', $journalGroup->getId());
        }

        $subjects =  $subjects->orderBy('tfc.id')
            ->getQuery()
            ->execute();

        $tfcList = [];
        foreach ($tfc as $item) {
            $tfcList[] = ['tfc'=>$item,'subjects'=>[]];
        }
        foreach ($tfcList as &$itemTfc){
            foreach ($subjects as $itemSubject){
                if($itemTfc['tfc']->getId() == $itemSubject->getTypeFormControl()->getId())
                    array_push($itemTfc['subjects'],$itemSubject);
            }
        }

        if(!$journalGroup){
            return $this->render('journal/Exception/error404.html.twig',['message_error'=>'Така група не інуснує.']);
        }

        $arrayStudent = [];

        foreach ($journalGroup->getStudents() as $student) {
            $arrayStudent[] = $student->getId();
        }

        return $this->render('journal/journal_group/one-group.html.twig',[
            'group'=>$journalGroup,
            'formControl'=>$tfc,
            'gradingSystem'=>$gradingSystem,
            'tfc'=>$tfcList,
            'menu'=>Service\Helper::createMenu($manager),
            'arrayStudent'=>json_encode($arrayStudent)
        ]);

    }

    /**
     * @Route("/journal/ajax/groupAdd", name="groupAdd", methods={"POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function groupAdd(Request $request,ObjectManager $manager)
    {
        $group = $manager->getRepository(JournalGroup::class)
            ->findBy(['name'=>trim($request->get('name'))]);

        if($group){
            return new JsonResponse(array('type' => 'error','message'=>'Така група вже інуснує.'));
        }

        $curator = $manager->getRepository(JournalGroup::class)
            ->find($request->get('teacher_id'));

        if($curator){
            return new JsonResponse(array('type' => 'error','message'=>'У куратора вже є група.'));
        }

        $curator = $manager->getRepository(JournalTeacher::class)
            ->find($request->get('teacher_id'));

        $specialty = $manager->getRepository(JournalSpecialty::class)
            ->find($request->get('specialty_id'));

        $group = new JournalGroup();
        $group->setName(trim($request->get('name')));
        $group->setDescription(trim($request->get('name')));
        $group->setCurator($curator);
        $group->setSpecialty($specialty);
        $manager->persist($group);
        $manager->flush();

        return new JsonResponse(array('type' => 'info','message'=>'Група додана додана.'));
    }

    /**
     * @Route("/journal/ajax/groupUpdate", name="groupUpdate", methods={"POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function groupUpdate(Request $request,ObjectManager $manager){

        $group = $manager->getRepository(JournalGroup::class)
            ->checkGroupName($request->get('group_name'), $request->get('group_id'));

        if($group) return new JsonResponse(array('type' => 'error','message'=>'Така група вже інуснує.'));

        $curator = $manager->getRepository(JournalGroup::class)
            ->checkGroupCurator($request->get('curator_id'), $request->get('group_id'));

        if($curator) return new JsonResponse(array('type' => 'error','message'=>'У куратора вже є група.'));

        $journalGroup =  $manager->getRepository(JournalGroup::class)
            ->getGroupByAlis($request->get('group_id'));

        $specialty =  $manager->getRepository(JournalSpecialty::class)->find($request->get('specialty_id'));

        if(!$specialty) return new JsonResponse(array('type' => 'error','message'=>'Такої спеціальності не існує.'));


        $curator =  $manager->getRepository(JournalTeacher::class)->find($request->get('curator_id'));

        $journalGroup->setName($request->get('group_name'));
        $journalGroup->setSpecialty($specialty);
        $journalGroup->setCurator($curator);
        $manager->persist($journalGroup);
        $manager->flush();

        return new JsonResponse(
            array('type' => 'info',
                'message'=>'Група оновлена.',
                'new_alis'=>Service\Helper::createAlias($request->get('group_name'))
            )
        );

    }

    /**
     * @Route("/journal/ajax/groupDelete", name="groupDelete", methods={"POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function groupDelete(Request $request,ObjectManager $manager){
        $group = $manager->getRepository(JournalGroup::class)->find($request->get('group_id'));
        $manager->remove($group);
        $manager->flush();

        return new JsonResponse(array('type' => 'error','message'=>'Група та предметы, студенти були видалені.'));
    }

}
