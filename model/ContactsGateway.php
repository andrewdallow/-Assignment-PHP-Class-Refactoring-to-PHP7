<?php


class ContactsGateway
{

    public function selectAll($order)
    {
        if (!isset($order)) {
            $order = "name";
        }
        $dbOrder = mysql_real_escape_string($order);
        $dbres = mysql_query("SELECT * FROM contacts ORDER BY $dbOrder ASC");

        $contacts = array();
        while (($obj = mysql_fetch_object($dbres)) != null) {
            $contacts[] = $obj;
        }

        return $contacts;
    }

    public function selectById($id)
    {
        $dbId = mysql_real_escape_string($id);

        $dbres = mysql_query("SELECT * FROM contacts WHERE id=$dbId");

        return mysql_fetch_object($dbres);

    }

    public function insert($name, $phone, $email, $address)
    {

        $dbName = ($name != null) ? "'" . mysql_real_escape_string($name) . "'"
            : 'NULL';
        $dbPhone = ($phone != null) ? "'" . mysql_real_escape_string($phone)
            . "'" : 'NULL';
        $dbEmail = ($email != null) ? "'" . mysql_real_escape_string($email)
            . "'" : 'NULL';
        $dbAddress = ($address != null) ? "'" . mysql_real_escape_string(
                $address
            ) . "'" : 'NULL';

        mysql_query(
            "INSERT INTO contacts (name, phone, email, address) VALUES ($dbName, $dbPhone, $dbEmail, $dbAddress)"
        );
        return mysql_insert_id();
    }

    public function delete($id)
    {
        $dbId = mysql_real_escape_string($id);
        mysql_query("DELETE FROM contacts WHERE id=$dbId");
    }

}