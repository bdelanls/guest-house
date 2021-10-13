<?php

namespace App\Controller;

use App\Model\ContactManager;

/**
 * Class ContactController
 *
 */
class ContactController extends AbstractController
{


    /**
     * Display contact listing
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $contactManager = new ContactManager();
        $contacts = $contactManager->selectAll();

        return $this->twig->render('Contact/index.html.twig', ['contacts' => $contacts]);
    }


    /**
     * Display contact informations specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function show(int $id)
    {
        $contactManager = new ContactManager();
        $contact = $contactManager->selectOneById($id);

        return $this->twig->render('Contact/show.html.twig', ['contact' => $contact]);
    }


    /**
     * Display contact edition page specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function edit(int $id): string
    {
        $contactManager = new ContactManager();
        $contact = $contactManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contact['title'] = $_POST['title'];
            $contactManager->update($contact);
        }

        return $this->twig->render('Contact/edit.html.twig', ['contact' => $contact]);
    }


    /**
     * Display contact creation page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function add()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contactManager = new ContactManager();



            $contact = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'subject' => $_POST['subject'],
                'message' => $_POST['message'],
            ];

            $message = true;

            $id = $contactManager->insert($contact);
            return $this->twig->render('Contact/add.html.twig', ['message' => $message, 'nameContact' => $contact['name']]);        }

        return $this->twig->render('Contact/add.html.twig');
    }


    /**
     * Handle contact deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $contactManager = new ContactManager();
        $contactManager->delete($id);
        header('Location:/contact/index');
    }
}
