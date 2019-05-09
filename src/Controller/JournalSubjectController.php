<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
