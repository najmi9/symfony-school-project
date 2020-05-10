<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfController extends AbstractController
{
    /**
     * @Route("/prof", name="prof")
     */
    public function index()
    {
        return $this->render('prof/index.html.twig', [
            'controller_name' => 'ProfController',
        ]);
    }
}
