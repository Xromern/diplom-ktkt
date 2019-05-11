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

            $curator_id = $curator->getCurator()->getId();

            $teacher = $teacher->orWhere('t.id = :curator')
                ->setParameter('curator',$curator_id);
        }

        $teacher =  $teacher->getQuery()->execute();

        $string = "";
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

}
