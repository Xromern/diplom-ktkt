<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class JournalTypeMarkController extends AbstractController
{
    /**
     * @Route("/journal/type/mark", name="journal_type_mark")
     */
    public function index()
    {
        return $this->render('journal_type_mark/index.html.twig', [
            'controller_name' => 'JournalTypeMarkController',
        ]);
    }
}
