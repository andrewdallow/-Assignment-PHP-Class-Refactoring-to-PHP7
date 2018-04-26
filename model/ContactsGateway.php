<?php

class ContactsGateway
{

    /**
     * @param null|string $order
     * @param mysqli      $db
     *
     * @return array
     * @throws Exception
     */
    public function selectAll(?string $order, mysqli $db): array
    {
        if ($order === null) {
            $order = 'name';
        }
        $dbOrder = mysqli_real_escape_string($db, $order);

        $dbres = $db->query("SELECT * FROM contacts ORDER BY $dbOrder ASC");

        if ($dbres) {
            $contacts = array();
            while (($obj = mysqli_fetch_object($dbres)) !== null) {
                $contacts[] = $obj;
            }
        } else {
            throw new Exception('No contacts table not found.');
        }
        return $contacts;
    }

    /**
     * @param int    $id
     * @param mysqli $db
     *
     * @return null|object
     */
    public function selectById(int $id, mysqli $db)
    {
        $dbId = mysqli_real_escape_string($db, $id);

        $dbres = $db->query("SELECT * FROM contacts WHERE id=$dbId");

        return mysqli_fetch_object($dbres);

    }

    /**
     * @param mysqli $db
     * @param string $name
     * @param string $phone
     * @param string $email
     * @param string $address
     *
     * @return int
     */
    public function insert(mysqli $db, string $name, string $phone,
        string $email, string $address
    ): int {
        $dbName = ($name !== null) ? "'" . mysqli_real_escape_string($db, $name)
            . "'"
            : 'NULL';
        $dbPhone = ($phone !== null) ? "'" . mysqli_real_escape_string(
                $db, $phone
            )
            . "'" : 'NULL';
        $dbEmail = ($email !== null) ? "'" . mysqli_real_escape_string(
                $db, $email
            )
            . "'" : 'NULL';
        $dbAddress = ($address !== null) ? "'" . mysqli_real_escape_string(
                $db,
                $address
            ) . "'" : 'NULL';

        $db->query(
            "INSERT INTO contacts (name, phone, email, address) VALUES ($dbName, $dbPhone, $dbEmail, $dbAddress)"
        );
        return $db->insert_id;
    }

    /**
     * @param mysqli $db
     * @param int    $id
     */
    public function delete(mysqli $db, int $id): void
    {
        $dbId = mysqli_real_escape_string($db, $id);
        $db->query("DELETE FROM contacts WHERE id=$dbId");
    }

}