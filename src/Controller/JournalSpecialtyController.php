<?php

namespace App\Controller;

use App\Entity\JournalGroup;
use App\Entity\JournalSpecialty;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request as Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
class JournalSpecialtyController extends AbstractController
{
    /**
     * @Route("/journal/specialtyShow", name="journal_specialty_show")
     */
    public function specialtyShow()
    {
        $em = $this->getDoctrine()->getManager();

        $specialty = $em
            ->getRepository(JournalSpecialty::class)
            ->findAll();

        $string = "";
        foreach ($specialty as $sp){
            $string.=$this->render('journal/journal_specialty/specialty-edit.html.twig', ['sp'=>$sp]);
        }

        return new Response($string);
    }

    /**
     * @Route("/journal/specialtyAdd", name="journal_specialty_add")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function specialtyAdd(Request $request,ObjectManager $manager)
    {

        $specialty = $manager->getRepository(JournalSpecialty::class)
            ->findBy(['name'=>trim($request->get('name'))]);

        if(count($specialty)>0){
            return new JsonResponse(array('type' => 'error','message'=>'Така спеціальність вже інуснує.'));
        }

        $specialty = new JournalSpecialty();
        $specialty->setName(trim($request->get('name')));
        $specialty->setDescription(trim($request->get('name')));
        $manager->persist($specialty);
        $manager->flush();


        return new JsonResponse(array('type' => 'info','message'=>'Спеціальність додана.'));
    }

    /**
     * @Route("/journal/specialtyDelete", name="journal_specialty_delete")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function specialtyDelete(Request $request,ObjectManager $manager)
    {
        $specialty = $manager->getRepository(JournalSpecialty::class)
            ->find($request->get('id'));

        if (!$specialty) {
            return new JsonResponse(array('type' => 'error','message'=>'Спеціальність не інуснує.'));
        }

        if(count($specialty->getGroups()) != 0){
            return new JsonResponse(array('type' => 'error','message'=>'Групи не можуть існувати без спеціальності.'));

        }

        $manager->remove($specialty);
        $manager->flush();

        return new JsonResponse(array('type' => 'info','message'=>'Спеціальність видалена.'));
    }

    /**
     * @Route("/journal/specialtyUpdate", name="journal_specialty_update")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function specialtyUpdate(Request $request,ObjectManager $manager)
    {

        $specialty = $manager->getRepository(JournalSpecialty::class)
            ->find($request->get('id'));

        if(!$specialty){
            return new JsonResponse(array('type' => 'error','message'=>'Така спеціальність не інуснує.'));
        }

        $specialty->setName(trim($request->get('name')));
        $specialty->setDescription(trim($request->get('name')));
        $manager->persist($specialty);
        $manager->flush();


        return new JsonResponse(array('type' => 'info','message'=>'Спеціальність оновлена.'));
    }

    /**
     * @Route("/journal/ajax/specialtySelect", name="journal_specialty_select")
     */
    public function specialtySelect(Request $request, ObjectManager $manager)
    {
        $specialty_id = null;
        if(($request->get('group_alis'))){
            $group = $manager->getRepository(JournalGroup::class)
                ->getGroupByAlis($request->get('group_alis'));
            $specialty_id = $group->getSpecialty()->getId();
        }

        $specialty = $manager->getRepository(JournalSpecialty::class)
            ->findAll();

        $string = "";
        foreach ($specialty as $item){
            if($item->getId() == $specialty_id){
                $selected = "selected";
            }else{
                $selected = "";
            }

            $string.="<option $selected value='".$item->getId()."'>".$item->getName()."</option>";
        }
       // dd(3);

        return new Response($string);
    }

}
