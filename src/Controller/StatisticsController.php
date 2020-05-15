<?php

namespace App\Controller;

use App\Services\StatisticService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StatisticsController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_home")
     */
    public function index(EntityManagerInterface $manager,
     StatisticService $statsService)
    {

        $stats      = $statsService->getStats();
      //  $bestAds    = $statsService->getAdsStats('DESC');
       // $worstAds   = $statsService->getAdsStats('ASC');

        return $this->render('admin/home.html.twig', [
            'stats'     => $stats,
           // 'bestAds'   => $bestAds,
           // 'worstAds'  => $worstAds
        ]);
    }
}
