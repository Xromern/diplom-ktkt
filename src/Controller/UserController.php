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
       $key = $manager->getRepository(JournalCode::class)->findOneBy(array('keyP'=>$request->get('key')));

       if(!$key){
           return new JsonResponse(array('type' => 'error','message'=>'Код невірний'));
       }

       if($key->getDateUse()){
           return new JsonResponse(array('type' => 'error','message'=>'Код вже використано'));

       }

       $this->getUser()->setRoles(array($key->getRole()));
        $key->setUser($this->getUser());
       $manager->persist($this->getUser());
       $manager->persist($key);
       $manager->flush();

        return new JsonResponse(array('type' => 'info','message'=>'Ви активували акаунт'));
    }
}