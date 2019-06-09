<?php

namespace App\Controller;

use App\Entity\JournalGroup;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as Request;
use Symfony\Component\Routing\Annotation\Route;

class Form6Controller extends AbstractController
{
    /**
     * @Route("/journal/group/{group_alis}/form6",
     *   name="journal_show_form6",
     *  )
     */
    public function index(Request $request, ObjectManager $manager)
    {
        $group = $manager->getRepository(JournalGroup::class)->getGroupByAlis($request->get('group_alis'));
        if(!$group) return $this->render('journal/journal_subject/subject.html.twig', ['message_error'=>'Група не знайдена']);

        return $this->render('journal/form6/form6.html.twig', [
            'group' => $group,
        ]);
    }
}
