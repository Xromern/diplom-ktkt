<?php

namespace App\Controller;

use App\Entity\JournalDateMark;
use App\Entity\JournalGroup;
use App\Entity\JournalMark;
use App\Service\Helper;
use App\Service\Journal;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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

        $dateDistinct  = $manager->getRepository(JournalDateMark::class)->getDistinctOnByGroup($group->getId());
        $option = '';

        $monthsList = array(
            "0." => "Січня",
            "02" => "Лютого",
            "03" => "Березная",
            "04" => "Квітня",
            "05" => "Травня",
            "06" => "Червня",
            "07" => "Липня",
            "08" => "Серпня",
            "09" => "Вересня",
            "10" => "Жовтня",
            "11" => "Листопада",
            "12" => "Грудня"
        );

        foreach ($dateDistinct as $item) {
            if ($item['dateFormat'] != null) {
                $dateArray = explode('-', $item['dateFormat']);
                $option .= '<option value="'.$item['dateFormat'].'">' . $item['dateFormat'] . ' ' . $monthsList[$dateArray[1]] . '</option>';
            }
        }
        return $this->render('journal/form6/form6.html.twig', [
            'group' => $group,
            'option'=> $option
        ]);
    }

    /**
     * @Route("/journal/ajax/form6Table", name="form6Table")
     */
    public function getTable(Request $request, ObjectManager $manager)
    {
     //   dd($request->get('group_id'));
        $group = $manager->getRepository(JournalGroup::class)->find($request->get('group_id'));
      //  dd($group);
        Helper::isEmpty($group);
        $dateArray = explode('-',$request->get('date'));

        $cal_days_in_month = cal_days_in_month(CAL_GREGORIAN, $dateArray[1], $dateArray[0]);

        $students = Journal::getForm6($group,$cal_days_in_month,$manager,$request->get('date'));

        return $this->render('journal/form6/form6-table.html.twig',array(
            'students'=>$students,
            'group'=>$group,
            'date'=>$request->get('date'),
            'cal_days_in_month'=>$cal_days_in_month
        ));
    }

    /**
     * @Route("/journal/ajax/form6UpdateMissed", name="form6UpdateMissed")
     */
    public function updateMissed(Request $request, ObjectManager $manager)
    {
        $missed = $request->get('missed');
        foreach ($request->get('mark_id') as $item){
            $mark = $manager->getRepository(JournalMark::class)->find($item);
            Helper::isEmpty($mark);
            $missed++;
            if($missed > 2)  $missed = 0;
            $mark->setMissed($missed);
            $manager->persist($mark);
        }
        $manager->flush();

        return new JsonResponse(array('type' => 'info','message'=>'Пропуск оновлено'));
    }



}
