<?php
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require __DIR__ . '/src/ActiveRecord.php';
require_once __DIR__ . '/vendor/autoload.php';
$loader = new FilesystemLoader(__DIR__ . '/views');
$twig = new Environment($loader);
echo $twig->render('index.html');

$activeRecord = new ActiveRecord();


if (isset($_GET['getAllRecords'])) {
    $result = $activeRecord->getAllRecords();
    foreach ($result as $record){
        $id = $record['id'];
        $login = $record['login'];
        $message = $record["message"];
        echo "<p>" . "ID : ". $id . "; Login : " . $login . "; Message : ". $message . "</p>";
    }
}

if (isset($_GET['getRecordID']) && isset($_GET['ID']) && (string)$_GET['ID'] !== '') {
    $id = $_GET['ID'];
    $result = $activeRecord->getIDRecord($id);
    $login = $result->getLogin();
    $message = $result->getMessage();
    echo "<p>" . "ID : ". $id . "; Login : " . $login . "; Message : ". $message . "</p>";
}

if (isset($_GET['getFilter']) && isset($_GET['Login']) && isset($_GET['dbLogin']) !== '')
{
    $login = $_GET['dbLogin'];
    $result = $activeRecord->getFilter($login);
    foreach ($result as $record){
        $id = $record["id"];
        $message = $record["message"];
        echo "<p>" . "ID : ". $id . "; Login : " . $login . "; Message : ". $message . "</p>";
    }
}


if (isset($_GET['saveRecord']) && isset($_GET['ID']) && isset($_GET['dbLogin']) && isset($_GET['dbMessage'])) {
    $id = $_GET['ID'];
    $login = $_GET['dbLogin'];
    $message = $_GET['dbMessage'];
    $addRecord = new ActiveRecord();
    $addRecord->setId($id);
    $addRecord->setLogin($login);
    $addRecord->setMessage($message);
    $addRecord->addRecord();
}

if (isset($_GET['deleteRecord']) && isset($_GET['ID']) && (string)$_GET['ID'] !== '')
{
    $id = $_GET['ID'];
    $result = $activeRecord->getIDRecord($id);
    $result->deleteRecord();
}



function addToHistory($login, $message){
    $messageJson = (object) ['user' => $login, 'message' => $message];
    $content = json_decode(file_get_contents("history.json"));
    $content->messages[] = $messageJson;
    file_put_contents("history.json", json_encode($content));
    
    $db = new PDO('mysql:dbname=chat;host=localhost', 'kailey', '12345');
    $stm = $db->prepare("insert into history(user,message) values ('$login','$message')");
    $stm->execute();
}

function printMessages(){
    $content = json_decode(file_get_contents("history.json"));
    foreach($content->messages as $message){
        echo "<p>";
        echo "$message->user: $message->message";
        echo "</p>";
    }
}

$adminLogin = "admin";
$adminPassword = "12345";

$login = $_GET["login"];
$password = $_GET["password"];
$message = $_GET["message"];    


if (($login === $adminLogin) && ($password === $adminPassword)){
    addToHistory($login, $message);
}
else{
    echo "Incorrect login or password";
}


printMessages();
?>