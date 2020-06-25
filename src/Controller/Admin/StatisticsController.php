<?php

namespace App\Controller\Admin;

use App\Services\StatisticService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class StatisticsController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_home")
     * @Security("is_granted('ROLE_ADMIN')", message="Access Denied")
     * 
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
