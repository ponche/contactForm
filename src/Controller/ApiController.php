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

        $contact = new Contact();
        $view = View::create();
        $view->setFormat('json');
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
            $view->setFormat('json');
            return $this->handleView($view);
        }

        $view = $this->view($form, Response::HTTP_BAD_REQUEST);
        $view->setFormat('json');
        return $this->handleView($view);
        
    }

    /**
     * @Get("/departments")
     */
    public function getDepartmentsAction()
    {
        $listDepartment = $this->departmentRepository->findAll(); 
        $view = View::create($listDepartment, Response::HTTP_OK);
        $view->setFormat('json'); 

        return $this->handleView($view);

    }
    /**
     * @Get("/department/{id}")
     */
    public function getDepartmentAction($id)
    {  
        $listDepartment = $this->departmentRepository->find($id); 
        $view = View::create($listDepartment, Response::HTTP_OK);
        $view->setFormat('json'); 

        return $this->handleView($view);
    }
}
