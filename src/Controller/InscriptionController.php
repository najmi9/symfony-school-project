<?php

namespace App\Controller;

use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\StudentRepository;
use App\Repository\UserRepository;
use App\Repository\StdPerInfoRepository;
use App\Entity\StdPerInfo;
use App\Entity\User;
use App\Form\UserType;
use App\Entity\StdCv;
use App\Form\StdCvType;
use App\Form\StdPerInfoType;
use App\Entity\StdChoice;
use App\Form\StdChoiceType;
use App\Entity\StdProfile;
use App\Form\StdProfileType;
use App\Entity\Student;
use App\Services\FileUploaderUserService;



class InscriptionController extends AbstractController
{
    /**
     * @Route("/student/inscription", name="inscription")
     */
    public function index(Request $request, EntityManagerInterface $manager
    , UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer,
    TokenGeneratorInterface $tokenGenerator, FileUploaderUserService $fileUploader)
    {
       
              $user = new User();
              $finStd = new Student();
              //Default state for the student, edited by th eadmin  
              $stdProfile = new StdProfile();
              $stdProfile->setState('NOT VERIFIED')
                           ->setNote(0);
              $form = $this->createForm(UserType::class, $user);
              $form->handleRequest($request);
               
              if ($form->isSubmitted() && $form->isValid()) {
                  
                   $pictureFile = $form['picture']->getData();
                   $pictureFileName = $fileUploader->upload($pictureFile);
                   $user->setPicture($pictureFileName);
              

                   $token =$tokenGenerator->generateToken();
                   $data = $request->request->get('user');
                   $user->setPassword(
                           $passwordEncoder->encodePassword(
                           $user,
                           $form->get('password')->getData()))
                        ->setActivationToken($token)
                   ;     
                   $finStd->setUser($user);
                   $finStd->setProfile($stdProfile);
                   $message =( new \Swift_Message("Confirmation de l'émail"))
                    ->setFrom('noreplynajmi@gmail.com')
                    ->setTo($data['email'])
                    ->setBody(
                            $this->renderView(                   
                            'std_state/email.html.twig',
                            [
                              'user' => $request->request->get('user'),
                              'token'=>$token
                            ]
                            )
                     , 'text/html');
                  $mailer->send($message);
                 
                  $manager->persist($user);
                  $manager->persist($finStd);
                  $manager->persist($stdProfile);
                  $manager->flush();
                   
                  $this->addFlash('success', 'Ok, You are added successfly, Please Verify your email to continue !');
                  return $this->redirectToRoute('home');
              }
            return $this->render('inscription/index.html.twig', [
             'stdForm'=>$form->createView()
            ]);
    }

    /**
     * @Route("/student/info/edit/{id}", name="student_edit_info")
     * @Route("/student/info/add", name="student_add_info")
     */
    public function editInfo(StdPerInfo $stdInfo=null, EntityManagerInterface 
      $manager, Request $request, StudentRepository $stdRepo)
    {
          
        if ($stdInfo) {
              $finStd =  $stdRepo->findOneByStdperinfo($stdInfo);
              $form = $this->createForm(StdPerInfoType::class, $stdInfo);
              $form->handleRequest($request);
       
              if ($form->isSubmitted() && $form->isValid()) {
                   $finStd->setStdperinfo($stdInfo);
                   $manager->persist($stdInfo);
                   $manager->persist($finStd);
                   $manager->flush();
                   
                   $this->addFlash('message', 'Ok, Your Information edited successfly');
                   return $this->redirectToRoute('student_profile',['id'=>$finStd->getId()]);
              }
        } else {
            $stdInfo = new StdPerInfo();
            $finStd = $stdRepo->findOneByUser($this->getUser());
            $form = $this->createForm(StdPerInfoType::class, $stdInfo);
            $form->handleRequest($request);
       
            if ($form->isSubmitted() && $form->isValid()) {
             
            $data = $request->request->get('std_per_info');
         // dd($data, $request->request);
  $date = $data['age']['day'].'-'.$data['age']['month'].'-'.$data['age']['year'];
               
                 $stdInfo->setAge(date_create($date))
                         ->setCin($data['cin'])
                         ->setGendre($data['gendre'])
                         ->setCity($data['city'])
                         ->setPhone($data['phone'])
                  ;
                  $finStd->setStdperinfo($stdInfo);
                  $manager->persist($stdInfo);
                  $manager->persist($finStd);
                  $manager->flush();
                   
                  $this->addFlash('message', 'Ok, Your Information added successfly');
                  return $this->redirectToRoute('student_profile',['id'=>$finStd->getId()]);
              }
        }
        
        return $this->render('inscription/index.html.twig', [
           'stdForm'=>$form->createView()
        ]);
    }

     /**
     * @IsGranted("ROLE_USER")
     * @Route("/student/inscription/edit/{id}", name="inscription_edit")
     */
    public function editInscription(User $user, Request $request, 
      EntityManagerInterface $manager, StudentRepository $finstdRepo
      , UserPasswordEncoderInterface $passwordEncoder,
    UserRepository $stdRepo, \Swift_Mailer $mailer, TokenGeneratorInterface$tokenGenerator, FileUploaderUserService $fileUploader)
    {

              $oldEmail = $user->getEmail();
              $finStd =  $finstdRepo->findOneByUser($user);
       
              $form = $this->createForm(UserType::class, $user);
              $form->handleRequest($request);
       
              if ($form->isSubmitted() && $form->isValid()) {
                 $pictureFile = $form['picture']->getData();
                   $pictureFileName = $fileUploader->upload($pictureFile);
                   $user->setPicture($pictureFileName);
                   $data = $request->request->get('user');
                   //Vérifier s'il y a un changement de l'email
                   if ($data['email'] != $oldEmail) {
                      $token =$tokenGenerator->generateToken();
                      $user->setEmail($data['email'])
                           ->setActivationToken($token)
                           ->setPassword(
                             $passwordEncoder->encodePassword(
                             $user,
                             $form->get('password')->getData()))
                           ->setName($data['name'])
                           ->setPicture($data['picture'])
                      ;
                      $message =( new \Swift_Message("Confirmation de l'email"))
                       ->setFrom('noreplynajmi@gmail.com')
                       ->setTo($data['email'])
                       ->setBody(
                            $this->renderView(                   
                            'std_state/email.html.twig',
                            [
                              'user' => $request->request->get('user'),
                              'token'=>$token
                            ]
                            )
                       , 'text/html');
                      $mailer->send($message);
                      $manager->persist($user);
                      $manager->flush();
                      /*$stdsec->logout();
                      $this->addFlash('success', 'Ok, Your Information edited successfly, Please Verify your email to continue !');*/
                      return $this->redirectToRoute('app_user_logout');
                   }
                   $user->setPassword(
                           $passwordEncoder->encodePassword(
                           $user,
                           $form->get('password')->getData()))
                        ->setName($data['name'])
                        ->setPicture($data['picture'])
                   ;
                   $finStd->setUser($user);
                  $manager->persist($user);
                  $manager->persist($finStd);
                  $manager->flush();
                   
                  $this->addFlash('success', 'Ok, Your Information edited successfly !');
                  return $this->redirectToRoute('student_profile', ['id'=>$finStd->getId()]);     
              }
        return $this->render('inscription/index.html.twig', [
           'stdForm'=>$form->createView()
        ]);
    }
    /**
     * @IsGranted("ROLE_USER")
     * @Route("student/cursur-accademique/{id}", name="cv_student")
     */
    
    public function studentCv(Request $request, StdPerInfo $std, EntityManagerInterface $manager)
    {

     $finStd = $manager->getRepository(Student::class)->findOneByStdperinfo($std);

    	if ($finStd->getStdcv()) {

        $stdCv = $finStd->getStdcv();

          $form = $this->createForm(StdCvType::class, $stdCv);
           $form->handleRequest($request);
             if ($form->isSubmitted() && $form->isValid()) {

              $day = $request->request->get('std_cv')['year']['day'];
              $month = $request->request->get('std_cv')['year']['month'];
              $year = $request->request->get('std_cv')['year']['year'];
              $date = $day.'-'.$month.'-'.$year;
                 $stdCv->setLevel($request->request->get('std_cv')['level'])
                       ->setSchool($request->request->get('std_cv')['school'])
                       ->setYear(date_create($date))
                       ->setDiplomes($request->request->get('std_cv')['moyen'])
                  ;     
                  $finStd->setStdcv($stdCv);
      
                  $manager->persist($stdCv);
                  $manager->persist($finStd);
                  $manager->flush();

                  $this->addFlash('success', 'Ok, Your Cv edited successfly!');
                  return $this->redirectToRoute('student_profile', ['id'=>$finStd->getId()]);      
             }

      }else{
            $stdCv = new StdCv();
      
             $form = $this->createForm(StdCvType::class, $stdCv);
             $form->handleRequest($request);
      
             if ($form->isSubmitted() && $form->isValid()) {
                 
                  $finStd->setStdcv($stdCv);
      
                  $manager->persist($stdCv);
                  $manager->persist($finStd);
                  $manager->flush();
                  $this->addFlash('message', 'Ok, You are added successfly!');
                  return $this->redirectToRoute('student_profile', ['id'=>$finStd->getId()]);
      
             }
           }

        return $this->render('inscription/cv.html.twig', [
           'stdCvForm'=>$form->createView()
        ]);
    }

     /**
     * @IsGranted("ROLE_USER")
     * @Route("/student/choice/{id}", name="choice_student")
     */   
    public function studentChoice(Request $request, User $user, EntityManagerInterface $manager)
    {
      $finStd = $manager->getRepository(Student::class)->findOneByUser($user);

      if ($finStd->getStdchoice()) {
       $stdChoice = $finStd->getStdchoice();
         $form = $this->createForm(StdChoiceType::class, $stdChoice);
             $form->handleRequest($request);
      
             if ($form->isSubmitted() && $form->isValid()) {
            
                $choice = $request->request->get('std_choice')['bactype'];
                 $stdChoice->setBactype($choice);
                 $finStd->setStdchoice($stdChoice);
                  $manager->persist($stdChoice);
                 $manager->persist($finStd);
                  $manager->flush();
                  $this->addFlash('success', 'Ok, Your Choice edited successfly!');
                 return $this->redirectToRoute('student_profile', ['id'=>$finStd->getId()]);
             }

      }else{
        $stdChoice = new StdChoice();
             $form = $this->createForm(StdChoiceType::class, $stdChoice);
             $form->handleRequest($request);
      
             if ($form->isSubmitted() && $form->isValid()) {
                  
                 $finStd->setStdchoice($stdChoice);
                  $manager->persist($stdChoice);
                 $manager->persist($finStd);
                  $manager->flush();
                  $this->addFlash('success', 'Ok, You are added successfly!');
                 return $this->redirectToRoute('student_profile', ['id'=>$finStd->getId()]);
             }
      }

        return $this->render('inscription/choice_student.html.twig', [
           'stdChoiceForm'=>$form->createView()
        ]);
    }



  }

     
