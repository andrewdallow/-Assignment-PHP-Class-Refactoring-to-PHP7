<?php

require_once 'model/ContactsService.php';

class ContactsController
{
    /** @var ContactsService */
    private $contactsService;

    public function __construct()
    {
        $this->contactsService = new ContactsService();
    }

    public function redirect(string $location): void
    {
        header('Location: ' . $location);
    }

    public function handleRequest(): void
    {
        $op = $_GET['op'] ?? null;
        try {
            if (!$op || $op === 'list') {
                $this->listContacts();
            } elseif ($op === 'new') {
                $this->saveContact();
            } elseif ($op === 'delete') {
                $this->deleteContact();
            } elseif ($op === 'show') {
                $this->showContact();
            } else {
                $this->showError(
                    'Page not found',
                    'Page for operation ' . $op . ' was not found!'
                );
            }
        } catch (Exception $e) {
            // some unknown Exception got through here, use application error page to display it
            $this->showError('Application error', $e->getMessage());
        }
    }

    public function listContacts(): void
    {
        $orderby = $_GET['orderby'] ?? null;
        try {
            $contacts = $this->contactsService->getAllContacts($orderby);
        } catch (Exception $error) {
            throw $error;
        }

        include 'view/contacts.php';
    }

    public function saveContact(): void
    {

        $title = 'Add new contact';

        $name = '';
        $phone = '';
        $email = '';
        $address = '';

        $errors = array();

        if (isset($_POST['form-submitted'])) {

            $name = $_POST['name'] ?? null;
            $phone = $_POST['phone'] ?? null;
            $email = $_POST['email'] ?? null;
            $address = $_POST['address'] ?? null;

            try {
                $this->contactsService->createNewContact(
                    $name, $phone, $email, $address
                );
                $this->redirect('index.php');
                return;
            } catch (Exception $e) {
                $errors = $e->getMessage();
            }
        }

        include 'view/contact-form.php';
    }

    public function deleteContact(): void
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            throw new Exception('Internal error.');
        }

        $this->contactsService->deleteContact($id);

        $this->redirect('index.php');
    }

    public function showContact(): void
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            throw new Exception('Internal error.');
        }
        try {
            $contact = $this->contactsService->getContact($id);
        } catch (Exception $exception) {
            $exception->getMessage();
        }


        include 'view/contact.php';
    }

    public function showError(string $title, string $message): void
    {
        include 'view/error.php';
    }

}