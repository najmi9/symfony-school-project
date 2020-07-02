<?php

namespace App\Controller\Std;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PayementRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Student;

/**
 * @IsGranted("ROLE_USER")
 */
class StdPayementController extends AbstractController
{
	/**
	 * @Route("/student/payement/{id}", name="std_payement")
	 */
	
	public function index(Student $student, PayementRepository $payementRepo) : Response
	{ 
		if (!$student) {
			throw new NotFoundException("Etudiant n'est pas existe !", 1);			
		}
		$this->denyAccessUnlessGranted('view', $student->getUser());

		$this->render('std_state/payement.html.twig', [
          'myPayements'=> $payementRepo->findByStudent($student)
		]);
	}
}
