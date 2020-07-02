<?php

namespace App\Controller\Payement;

use App\Repository\PayementRepository;
use App\Repository\DepayementRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Depayement;
use App\Form\DepayementType;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class AddDepayementController extends AbstractController
{   
	/**
	 * @Route("/admin/add/depayement", name="admin_add_depayement")
	 * @Route("/admin/add/depayement/{id}", name="admin_edit_depayement")
	 */
	public function depayement(Depayement $depayement=null, DepayementRepository $depayementRepo, Request $request, EntityManagerInterface $em) : Response
	{ 
	    if ($depayement) {
		   $form = $this->createForm(DepayementType::class, $depayement);
		   $form->handleRequest($request);
           if ($form->isSubmitted() && $form->isValid()) {
           	  $month->setName($form->get('name')->gatDate())
                    ->setOutputs($form->get('outputs')->gatDate())
                    ->setInputs($form->get('inputs')->gatDate())
           	  ;
        	  $em->persist($depayement);
        	  $em->flush();
        	  $this->addFlash("info", "Votre depaiement est bien modifiée !");
        	  return $this->redirectToRoute('admin_add_depayement');
           }
	    }
		$depayement = new Depayement();
		$form = $this->createForm(DepayementType::class, $depayement);
		$form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        	$em->persist($depayement);
        	$em->flush();
        	$this->addFlash("info", "Votre depaiement est bien ajouté !");
        	return $this->redirectToRoute('admin_add_depayement');
        }
		return $this->render('admin/payement/depayement.html.twig', [
         'depayements'=>$depayementRepo->findAll(),
         'depayementForm'=>$form->createView()
		]);
	}
    
    /**
     * @Route("/admin/payement/depayement/remove/{id}", name="admin_remove_depayement")
     * @param  EntityManagerInterface $em    [description]
     */
	public function removeRepayement(Depayement $depayement, EntityManagerInterface $em): Response
	{
		if (!$depayement) {
			throw new NotFoundException("Le depayement n'est pas !", 1);			
		}
        $em->remove($depayement);
        $em->flush();
        $this->addFlash("info", "Votre depaiement est bien supprimer !");
        return $this->redirectToRoute('admin_add_depayement');
	}
}
