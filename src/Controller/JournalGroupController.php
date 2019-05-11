<?php

namespace App\Controller;


use App\Entity\JournalGroup;
use App\Entity\JournalSpecialty;
use App\Entity\JournalTeacher;
use App\Repository\TeacherRepository;
use App\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request as Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Common\Persistence\ObjectManager;

class JournalGroupController extends AbstractController
{
    /**
     * @Route("/journal", name="journal_group")
     */
    public function listGroup(Request $request, ObjectManager $manager)
    {
        $specialty = $manager->getRepository(JournalSpecialty::class)->findAll();

        if ($request->isXmlHttpRequest())
        {
            return $this->render('journal/journal_group/group-block.html.twig', [
                'controller_name' => 'JournalGroupController',
                'specialty'=>$specialty
            ]);
        }else{
            return $this->render('journal/journal_group/list-group-specialty.html.twig', [
                'controller_name' => 'JournalGroupController',
                'specialty'=>$specialty
            ]);
        }
    }

    /**
     * @Route("/journal/group/{group_alis}", name="journal_show_one_group")
     */
    public function showGroup(Request $request,ObjectManager $manager)
    {
        //$journalGroup = $manager->getRepository(JournalGroup::class)->find($request->get('group_alis'));

        $journalGroup =  $manager->getRepository(JournalGroup::class)
            ->getGroupByAlis($request->get('group_alis'));

        if(!$journalGroup){
            return $this->render('journal/Exception/error404.html.twig',['message_error'=>'Така група не інуснує.']);
        }

        return $this->render('journal/journal_group/one-group.html.twig',['group'=>$journalGroup]);

    }

    /**
     * @Route("/journal/ajax/groupAdd", name="groupAdd")
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
     * @Route("/journal/ajax/groupUpdate", name="groupUpdate")
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
     * @Route("/journal/ajax/groupDelete", name="groupDelete")
     */
    public function groupDelete(Request $request,ObjectManager $manager){
        $group = $manager->getRepository(JournalGroup::class)->find($request->get('group_id'));
        $manager->remove($group);
        $manager->flush();

        return new JsonResponse(array('type' => 'error','message'=>'Група та предметы, студенти були видалені.'));
    }

}
