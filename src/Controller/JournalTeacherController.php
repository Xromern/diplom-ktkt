<?php

namespace App\Controller;

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
    public function curatorSelect(ObjectManager $manager)
    {
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
}
