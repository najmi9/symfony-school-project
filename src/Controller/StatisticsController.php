<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\StudentRepository;
use App\Repository\ProfRepository;

class StatisticsController extends AbstractController
{
    /**
     * @Route("/statistics", name="admin_statistics")
     */
    public function index(StudentRepository $stdRepo, ProfRepository $profRepo)
    {

        return $this->render('statistics/index.html.twig', [
           'stds'=>$stdRepo->findAll(),
           'profs'=>$profRepo->findAll()
        ]);
    }
}
