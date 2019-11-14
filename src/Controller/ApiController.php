<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\DepartmentRepository;
use App\Services\MailManager;
use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;

/**
 * @Route("/api")
 */
class ApiController extends AbstractFOSRestController
{
    private $mailManager;
    private $departmentRepository;
    private $em; 

    public function __construct(DepartmentRepository $departmentRepository)
    {
       $this->departmentRepository = $departmentRepository;
    }
    
    /**
     * @Post("/contact")
     */
    public function postContactsAction(Request $request, MailManager $mailManager)
    {

        //code copier de Daishi

        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact, [
            'csrf_protection' => false,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact->setCreateAt(new \DateTime()); 

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();

            $mailManager->sendMailToDepartment($contact); 


            $view = $this->view($contact, Response::HTTP_CREATED);

            return $this->handleView($view);
        }

        $view = $this->view($form, Response::HTTP_BAD_REQUEST);

        return $this->handleView($view);
        // $contact = new Contact(); 

        // $form = 
        // // Fictive; 
        // // recupere le departement dev ( id=1 ) pour le test 
        // $department = $this->departmentRepository->find(1);

        // // creation d'un contact test 
        // $contact
        //     ->setFirstname("Rémi")
        //     ->setLastname("PONCHE")
        //     ->setMail("ponche62880@gmail.com")
        //     ->setDepartment($department)
        //     ->setMessage("Envoyé via une requete API")
        //     ->setCreateAt(new \DateTime())
        //     ;

        // // sauvegarde dans la BDD 
        // $em = $this->getDoctrine()->getManager(); 
        // $em->persist($contact);
        // $em->flush(); 


        // vérifie les data  si erreur lever une excepetion 
        // return (Exception )

        // $mailManager->sendMailToDepartment($contact); 

        // $dataTest = ["is Good"];
        // $view = View::create($dataTest, 200); 
        // $view->setFormat('json'); 

        // return $this->handleView($view);        
    }

    /**
     * @Get("/departments")
     */
    public function getDepartmentsAction()
    {
        $listDepartment = $this->departmentRepository->findAll(); 
        dump($listDepartment); 
        $dataTest = ['toto', 'titi']; 
        $view = View::create($dataTest, 200);
        $view->setFormat('json'); 
        dump($view); 

        // l'envoi marche faudrau juste filtrer 

        // $view = $this->view($listDepartment, 200); 

        // supprimer les mail et les relations dans listDepartment

        // envoi les data à la vue  

        return $this->handleView($view);

    }

    /**
     * @Route("/test")
     */
    public function test(DepartmentRepository $departmentRepository)
    {
        $listDepartment = $departmentRepository->findAll(); 
        dump($listDepartment); 

        return $this->render('contact/notification.html.twig');
    }


    public function postPromotionAction(Request $request)
    {
        $promotion = new Promotion();
        $form = $this->createForm(PromotionType::class, $promotion, [
            'csrf_protection' => false,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($promotion);
            $entityManager->flush();

            $view = $this->view($promotion, Response::HTTP_CREATED);

            return $this->handleView($view);
        }

        $view = $this->view($form, Response::HTTP_BAD_REQUEST);

        return $this->handleView($view);
    }
}
