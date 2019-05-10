<?php

namespace App\Controller;

use App\Entity\JournalGroup;
use App\Entity\JournalSpecialty;
use App\Entity\JournalTeacher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request as Request;

use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Common\Persistence\ObjectManager;
class JournalTeacherController extends AbstractController
{
    /**
     * @Route("/journal/curatorSelect", name="journal_curator_select")
     */
    public function curatorSelect(Request $request, ObjectManager $manager)
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }

        $teacher = $manager->getRepository(JournalTeacher::class)->createQueryBuilder('t')
            ->leftJoin('t.group', 'tg')
            ->andWhere('tg IS NULL')
            ->getQuery()
            ->execute();

        $string = "";
        foreach ($teacher as $item){
            $string.="<option value='".$item->getId()."'>".$item->getName()."</option>";
        }

        return new Response($string);
    }

    /**
     * @Route("/journal/ajax/curatorSelectEditGroup", name="curatorSelectEditGroup")
     */
    public function curatorSelectEditGroup(Request $request,ObjectManager $manager)
    {
        $curator = $manager->getRepository(JournalGroup::class)
            ->getCuratorByAlis($request->get('group_alis'));

        $curator_id = $curator->getCurator()->getId();

        $teachers = $manager->getRepository(JournalTeacher::class)->createQueryBuilder('t')
            ->leftJoin('t.group', 'tg')
            ->andWhere('tg IS NULL')
            ->orWhere('t.id = :curator')
            ->setParameter('curator',$curator_id)
            ->getQuery()
            ->execute();

        $string = "";

        foreach ($teachers as $item){

            if($item->getId() == $curator_id){
                $selected = "selected";
            }else{
                $selected = "";
            }

            $string.="<option value='".$item->getId()."' $selected value='".$item->getId()."'>".$item->getName()."</option>";
        }

        return new Response($string);
    }
}
