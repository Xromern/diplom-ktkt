<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class JournalStudentController extends AbstractController
{
    /**
     * @Route("/journal/student", name="journal_student")
     */
    public function index()
    {
        /*return $this->render('journal_student/index.html.twig', [
            'controller_name' => 'JournalStudentController',
        ]);*/
    }
}
