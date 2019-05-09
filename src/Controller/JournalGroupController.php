<?php

namespace App\Controller;


use App\Entity\JournalGroup;
use App\Entity\JournalSpecialty;
use App\Entity\JournalTeacher;
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
    public function listGroup(Request $request)
    {
        $em =  $this->getDoctrine();

        $specialty = $em->getRepository(JournalSpecialty::class)->findAll();

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
     * @Route("/journal/groupAdd", name="groupAdd")
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


}
