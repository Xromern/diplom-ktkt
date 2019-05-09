<?php

namespace App\Controller;

use App\Entity\JournalSpecialty;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request as Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Common\Persistence\ObjectManager;

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
     */
    public function specialtyDelete(Request $request,ObjectManager $manager)
    {
        $specialty = $manager->getRepository(JournalSpecialty::class)
            ->find($request->get('id'));

        if (!$specialty) {
            return new JsonResponse(array('type' => 'error','message'=>'Спеціальність не інуснує.'));
        }

        $manager->remove($specialty);
        $manager->flush();

        return new JsonResponse(array('type' => 'info','message'=>'Спеціальність видалена.'));
    }

    /**
     * @Route("/journal/specialtyUpdate", name="journal_specialty_update")
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
     * @Route("/journal/specialtySelect", name="journal_specialty_select")
     */
    public function specialtySelect(ObjectManager $manager)
    {

        $specialty = $manager->getRepository(JournalSpecialty::class)
            ->findAll();

        $string = "";
        foreach ($specialty as $item){
            $string.="<option value='".$item->getId()."'>".$item->getName()."</option>";
        }

        return new Response($string);
    }
}
