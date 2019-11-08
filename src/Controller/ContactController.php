<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Contact;
use App\Form\ContactType;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, \Swift_Mailer $mailer)
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $contact->setCreateAt(new \DateTime());
            $manager->persist($contact);

            $manager->flush();


            // send a mail to depatment 

            $message = new \Swift_Message('Contact');
            $message
                ->setFrom('hello@remi-ponche.fr', 'Efficience IT')
                ->setTo($contact->getDepartment()->getMail())
                ->setBody(
                    $this->renderView(
                        'mail/contact.txt.twig',
                        [
                            'contact' => $contact,
                        ]
                    ),
                    'text/plain'
                );

            $mailer->send($message);

            return $this->render('contact/notification.html.twig');
        }



        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'form' => $form->createView(),
        ]);
    }
}
