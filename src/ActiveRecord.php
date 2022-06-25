<?php

use PDO;

class ActiveRecord
{
    private $id;
    private $login;
    private $message;

    public function __construct()
    {
        $this->l = new PDO('mysql:dbname=ardb;host=localhost', 'kailey', '12345');
    }

    public function getId() {return $this->id;}

    public function setId($id) {$this->id = $id;}

    public function getLogin() {return $this->login;}

    public function setLogin($login) {$this->login = $login;}

    public function getMessage() {return $this->msg;}

    public function setMessage($message) {$this->message=$message;}


    public function getAllRecords(){
        $query = "SELECT * FROM messages";
        $db = $this->l->prepare($query);
        $db->execute();
        return $db->fetchAll();
    }

    public function getIDRecord($id) {
        $query = "SELECT * FROM messages WHERE id = $id";
        $statement = $this->l->prepare($query);
        $statement->execute();
        $res = $statement->fetchAll()[0];
        $NewInfo = null;

        if (isset($res)) {
            $NewInfo = new ActiveRecord();
            $NewInfo->setId($res['id']);
            $NewInfo->setLogin($res['login']);
            $NewInfo->setMessage($res['message']);
        }
        return $NewInfo;
    }

    public function getFilter($login)
    {
        $command = "SELECT * FROM messages WHERE login = 'user'";
        $db = $this->l->prepare($command);
        $db->execute();
        return $db->fetchAll();
    }

    public function addRecord() {
        $id = $this->id;
        $login = $this->login;
        $message = $this->message;

        if ($id != '' && $login != '' &&  $message != ''){
            $command = "INSERT INTO messages(id,login,message) VALUES ($id,'$login','$message');";
            $db = $this->l->prepare($command);
            $db->execute();
        }
    }

    public function deleteRecord() {
        $id = $this->id;
        $command = "DELETE FROM messages WHERE id = $id";
        $db = $this->l->prepare($command);
        $db->execute();
    }


}
