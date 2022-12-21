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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends AbstractController
{

    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    #[Route('/', name:'homePage')]


    public function homePage(): RedirectResponse
    {
        return $this->redirectToRoute("contacts");
    }

    #[Route('/contacts/create', name:'newContact')]

    public function createContact(Request $request): Response
    {
        $contact = new Contact();
        $form = $this->createForm(newContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { 
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

    #[Route('/contacts/{id}', name: 'contactId')]
    public function contactDetails($id) : Response
    {
        $contact = $this->contactRepository->find($id);
        return $this->render('contactDetails.html.twig', [
            'id' => $contact->getId(),
            'firstName' => $contact->getFirstName(),
            'lastName' => $contact->getLastName(),
            'phone' => $contact->getPhone(),
            'email' => $contact->getEmail(),
            'note' => $contact->getNote(),
        ]);
    }

    #[Route('/contacts/update/{lastName}/{id}', name: 'updateContact')]

    public function updateContact($id, $lastName, Request $request) 
    {
        $contact = $this->contactRepository->find($id);
        $form = $this->createForm(NewContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()) { 
            $this->contactRepository->save($contact);
            $this->addFlash('success', $contact->getFirstName() ."". $contact->getLastName(). "was successfully updated");
            return $this->redirectToRoute('contacts');
        }

        return $this->render('form.html.twig', [
            'form' => $form,
        ]);
       
    }

    #[Route('/contacts/delete/{id}', name: 'deleteContact')]
    public function deleteContact ($id): RedirectResponse
    {
        $contact = $this->contactRepository->find($id);
        if(!empty($contact)){
            $this->contactRepository->remove($contact);
            $this->addFlash('success', $contact->getFirstName() . " " . $contact->getLastName() . " was successfully deleted");
        }


        return $this->redirectToRoute('contacts');
    }
}