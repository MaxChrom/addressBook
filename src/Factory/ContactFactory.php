<?php 

namespace App\Factory;

use App\Entity\Contact;
// use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Form;

class ContactFactory
{

    public static function createForm(FormInterface $form): Contact
    {
        $contact = new Contact();

        $contact->setFirstName($form->get('firstName')->getData());
        $contact->setLastName($form->get('lastName')->getData());
        $contact->setPhone($form->get('phone')->getData());
        $contact->setEmail($form->get('email')->getData());
        $contact->setNote($form->get('note')->getData());

        return $contact;
    }
}