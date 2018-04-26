<?php

require_once 'model/ContactsGateway.php';
require_once 'model/ValidationException.php';


class ContactsService
{

    private $contactsGateway;
    private $db;

    /**
     * @throws Exception
     */
    private function openDb(): void
    {
        try {
            $this->db = new mysqli('localhost', 'root', '');

            if ($this->db->connect_error) {
                throw new Exception(
                    'Connection to the database server failed!'
                );
            }
            if (!mysqli_select_db($this->db, 'mvc-crud')) {
                throw new Exception(
                    'No mvc-crud database found on database server.'
                );
            }
        } catch (mysqli_sql_exception $exception) {
            throw $exception;
        }

    }

    private function closeDb(): void
    {
        if (!$this->db->connect_error) {
            mysqli_close($this->db);
        }
    }

    /**
     * ContactsService constructor.
     */
    public function __construct()
    {
        $this->contactsGateway = new ContactsGateway();
    }

    /**
     * @param string $order
     *
     * @return array|null
     * @throws Exception
     */
    public function getAllContacts(?string $order): ?array
    {
        try {
            $this->openDb();
            $res = $this->contactsGateway->selectAll($order, $this->db);
            $this->closeDb();
            return $res;
        } catch (Exception $e) {
            $this->closeDb();
            throw $e;
        }
    }

    /**
     * @param int $id
     *
     * @return null|object
     * @throws Exception
     */
    public function getContact(int $id)
    {
        try {
            $this->openDb();
            $res = $this->contactsGateway->selectById($id, $this->db);
            $this->closeDb();
            return $res;
        } catch (Exception $e) {
            $this->closeDb();
            throw $e;
        }
    }

    /**
     * @param string $name
     * @param string $phone
     * @param string $email
     * @param string $address
     *
     * @throws ValidationException
     */
    private function validateContactParams(string $name, string $phone,
        string $email, string $address
    ): void {
        $errors = array();
        if ($name === null || empty($name)) {
            $errors[] = 'Name is required';
        }
        if (empty($errors)) {
            return;
        }
        throw new ValidationException($errors);
    }

    /**
     * @param string $name
     * @param string $phone
     * @param string $email
     * @param string $address
     *
     * @return int|null
     * @throws Exception
     */
    public function createNewContact(string $name, string $phone, string $email,
        string $address
    ): ?int {
        try {
            $this->openDb();
            $this->validateContactParams($name, $phone, $email, $address);
            $res = $this->contactsGateway->insert(
                $this->db,
                $name, $phone, $email, $address
            );
            $this->closeDb();
            return $res;
        } catch (Exception $e) {
            $this->closeDb();
            throw $e;
        }
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public function deleteContact(int $id): void
    {
        try {
            $this->openDb();
            $this->contactsGateway->delete($this->db, $id);
            $this->closeDb();
        } catch (Exception $e) {
            $this->closeDb();
            throw $e;
        }
    }


}
