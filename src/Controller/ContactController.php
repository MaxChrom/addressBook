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
    // #[Route('/', name: 'app_contact')]
    // public function index(ContactRepository $contactRepository): Response
    // {


  
    //     $contacts = $contactRepository->findAll();

    //     return $this->render('contact/index.html.twig', [
    //         'contacts' => $contacts,
    //     ]);
    // }




    // #[Route('/create', name: 'createContact')]
    // public function createContact(ManagerRegistry $doctrine): Response
    // {
    //     $entityManager = $doctrine->getManager();

    //     $contact = new Contact();
    //     $contact->setFirstName('Maxim');
    //     $contact->setFirstName('Khromov');
    //     $contact->setPhone("12345678");
    //     $contact->setEmail('test@gmail.com');
    //     $contact->setNote('This guy is AWSOME!');

    //     // tell Doctrine you want to (eventually) save the Product (no queries yet)
    //     $entityManager->persist($contact);

    //     // actually executes the queries (i.e. the INSERT query)
    //     $entityManager->flush();

    //     // return new Response('Saved new product with id ' . $contact->getId());
    //     return $this->render('contact/create.html.twig', [
    //         'contact' => $contact,
    //     ]);
    // }

    // #[Route('/contacts/create', name: 'createContact')]
    // public function createContact(Request $request)
    // {
    //     $createContact = new Contact();
    //     $form = $this->createForm(newContactType::class, $createContact);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         // perform some action, such as saving the entity to the database
    //         // return $this->redirectToRoute('/contact/create');

    //     }

    //     return $this->render('form.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    //     }

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
            
        }

        return $this->render('form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}