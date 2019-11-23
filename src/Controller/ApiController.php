<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\DepartmentRepository;
use App\Services\MailManager;
use DateTime;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\View\View;

/**
 * @Route("/api")
 */
class ApiController extends AbstractFOSRestController
{
    private $departmentRepository;
    private $contact;

    public function __construct(DepartmentRepository $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
        $this->contact = new Contact();
        $this->contact->setCreateAt(new DateTime());
    }

    /**
     * @Post("/contact")
     */
    public function postContactsAction(Request $request, MailManager $mailManager)
    {

        $form = $this->createForm(ContactType::class, $this->contact, [
            'csrf_protection' => false,
        ]);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($this->contact);
            $entityManager->flush();

            $mailManager->sendMailToDepartment($this->contact);

            $view = $this->view($this->contact, Response::HTTP_CREATED);
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
}
