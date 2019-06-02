<?php

namespace App\Controller;

use App\Entity\JournalCode;
use App\Entity\JournalTeacher;
use App\Entity\JournalTypeMark;
use Doctrine\Common\Persistence\ObjectManager;
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request as Request;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends AbstractController
{

    /**
     * @Route("/useKey", name="useKey")
     */
    public function useKey(Request $request, ObjectManager $manager)
    {
       $key = $manager->getRepository(JournalCode::class)->findBy(array('keyP'=>$request->get('key')));

       if(!$key){
           return new JsonResponse(array('type' => 'error','message'=>'Код невірний'));

       }
    }
}