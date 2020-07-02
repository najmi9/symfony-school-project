<?php

namespace App\Controller\Payement;

use App\Repository\PayementRepository;
use App\Repository\MonthRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Month;
use App\Form\MonthType;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class AddMonthController extends AbstractController
{   
	/**
	 * @Route("/admin/add/month", name="admin_add_month")
	 * @Route("/admin/add/month/{id}", name="admin_edit_month")
	 */
	public function month(Month $month=null, MonthRepository $monthRepo, Request $request, EntityManagerInterface $em) : Response
	{ 
	    if ($month) {
		   $form = $this->createForm(MonthType::class, $month);
		   $form->handleRequest($request);
           if ($form->isSubmitted() && $form->isValid()) {
           	  $month->setName($form->get('name')->gatDate())
                    ->setOutputs($form->get('outputs')->gatDate())
                    ->setInputs($form->get('inputs')->gatDate())
           	  ;
        	  $em->persist($month);
        	  $em->flush();
        	  $this->addFlash("info", "Votre mois est bien modifiée !");
        	  return $this->redirectToRoute('admin_add_month');
           }
	    }
		$month = new Month();
		$form = $this->createForm(MonthType::class, $month);
		$form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        	$em->persist($month);
        	$em->flush();
        	$this->addFlash("info", "Votre mois est bien ajouté !");
        	return $this->redirectToRoute('admin_add_month');
        }
		return $this->render('admin/payement/month.html.twig', [
         'months'=>$monthRepo->findAll(),
         'monthForm'=>$form->createView()
		]);
	}
    
    /**
     * @Route("/admin/payement/month/remove/{id}", name="admin_remove_month")
     * @param  Moth                   $month [description]
     * @param  EntityManagerInterface $em    [description]
     * @return [type]                        [description]
     */
	public function removeMonth(Month $month, EntityManagerInterface $em): Response
	{
		if (!$month) {
			throw new NotFoundException("Le mois n'est pas !", 1);			
		}
        $em->remove($month);
        $em->flush();
        $this->addFlash("info", "Votre mois est bien supprimer !");
        return $this->redirectToRoute('admin_add_month');
	}
}
