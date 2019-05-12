<?php

namespace App\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as Request;
use Symfony\Component\Routing\Annotation\Route;

class JournalSubjectController extends AbstractController
{
    /**
     * @Route("/journal/subject", name="journal_subject")
     */
    public function index()
    {
        return $this->render('journal_subject/index.html.twig', [
            'controller_name' => 'JournalSubjectController',
        ]);
    }

    /**
     * @Route("/journal/ajax/subjectAdd", name="subjectAdd")
     */
    public function addSubject(Request $request, ObjectManager $manager)
    {
       dd(json_decode($request->get('list_student')));
    }
}
