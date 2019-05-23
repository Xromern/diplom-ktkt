<?php

namespace App\Controller;

use App\Entity\JournalTypeMark;
use Doctrine\Common\Persistence\ObjectManager;
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as Request;
use Symfony\Component\Routing\Annotation\Route;

class JournalTypeMarkController extends AbstractController
{
    /**
     * @Route("/journal/ajax/typeMark", name="journal_type_mark")
     */
    public function index(Request $request,ObjectManager $manager)
    {
        $typeMark = $manager->getRepository(JournalTypeMark::class)->findAll();

        $string = '';
        foreach ($typeMark as $type){
            $string.="<option value='".$type->getId()."'>".$type->getName()."</option>";
        }
        return new \Symfony\Component\HttpFoundation\Response($string);
    }
}
