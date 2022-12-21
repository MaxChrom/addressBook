<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Factory\ContactFactory;
use App\Repository\ContactRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\All;
use App\Form\NewContactType;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends AbstractController
{

    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }


    #[Route('/contacts/create')]

    public function createContact(Request $request): Response
    {
        $contact = new Contact();
        $form = $this->createForm(newContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted()) { //  && $form->isValid()
            // perform some action, such as saving the entity to the database
            // return $this->redirectToRoute('/contact/create');
            $contact = ContactFactory::createForm($form);
            $this->contactRepository->save($contact);
            $this->addFlash('success', "New contact was successfully created!");
            return $this->redirectToRoute('contacts');
        }

        return $this->render('form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/contacts', name:'contacts')]
    
    public function allContacts()
    {
        $contacts = $this->contactRepository->findAll();
        return $this->render("allContacts.html.twig", [
            'contacts' => $contacts,
        ]);
    }
}