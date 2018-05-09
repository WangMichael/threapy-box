<?php

declare(strict_types=1);

namespace application\login;

use framework\container\containerInterface;

class loginModel implements loginModelInterface
{

    private $container;


    private $target;


    private $populate = [];


    public function __construct(containerInterface $container)
    {
        $this->container    = $container;
    }

    public function getContainer(): containerInterface
    {
        return $this->container;
    }


    public function checkLogin() : void
    {

        if(isset($_GET['target']))
            $this->target = $_GET['target'];

        if(empty($_POST['username']) && empty($_POST['password']) && empty($_POST['loginCSRF']))
            return;

        if(empty($_POST['username'])){
            trigger_error('Please enter your username', E_USER_WARNING);
            return;
        }
        $this->populate['username'] = $_POST['username'];

        if(empty($_POST['password'])){
            trigger_error('Please enter your password', E_USER_WARNING);
            return;
        }

        if(empty($_POST['loginCSRF'])){
            trigger_error('the token is missing, Please refresh the form and sign in again', E_USER_WARNING);
            return;
        }

        if(!$this->checkCSRF('loginCSRF',$_POST['loginCSRF'])){
            trigger_error('The form has expired, please refresh the form', E_USER_WARNING);
            return;
        }


        $fields = Array(
            'userID'    => 'userID',
            'username'  => 'userName',
            'email'     => 'userEmail',
            'hash'      => 'userPassword'
        );

        $table = 'user';

        $params = Array(
            'where' => Array('`userName` = ?'),
            'show'  => 1
        );
        $data   = array($_POST['username']);
        $types  = array('s');

        $database   = $this->getContainer()->get('database');

        $query      = $database->getSelectSql($fields, $table, $params);

        if(false === $data = $database->query($query, $data, $types)){
            // user does not exist in the database
            trigger_error('The username/password is not correct', E_USER_WARNING);
            return;
        }
        $data = current($data);
        if(!password_verify($_POST['password'], $data['hash'])){
            // user does not exist in the database
            trigger_error('The username/password is not correct', E_USER_WARNING);
            return;
        }

        if(!session_id())
            session_start();
        session_regenerate_id();
        $_SESSION['username']       = $data['username'];
        $_SESSION['email']          = $data['email'];
        $_SESSION['userID']         = $data['userID'];
        $_SESSION['fingerprint']    = $this->getFingerPrint($data['username'], $data['email']);


        // redirect the page
        $target = "/";
        if(!empty($this->target))
            $target = $this->target;
        header('Location: '.$target, true,301);
        exit();

    }


    public function checkCSRF(string $identifier, string $token) : bool
    {
        if(!session_id())
            session_start();

        if(!isset($_SESSION[$identifier]))
            return false;

        return $token == $_SESSION[$identifier];
    }


    public function checkSignUp() : void
    {


        $_POST = array_merge($_FILES, $_POST);

        if(empty($_POST['username']) && empty($_POST['email']) && empty($_POST['password']) && empty($_POST['verify']) && empty($_POST['registerCSRF']) && empty($_POST['profile']))
            return;
        // sanitise and validate the username
        if(empty($_POST['username'])){
            trigger_error('Please enter the username', E_USER_WARNING);
            return;
        }
        $username = $_POST['username'];
        trim($username);
        if(!ctype_alnum($username)){
            trigger_error('The username must be alphanumeric', E_USER_WARNING);
            return;
        }elseif(strlen($username) < 8 || strlen($username) > 20){
            trigger_error('The username must be between 8 to 20 characters', E_USER_WARNING);
            return;
        }
        $this->populate['username'] = $username;



        //sanitise and validate the email
        if(empty($_POST['email'])){
            trigger_error('Please enter the email', E_USER_WARNING);
            return;
        }
        $email  = $_POST['email'];
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            trigger_error('The email is not valid', E_USER_WARNING);
            return;
        }
        $this->populate['email'] = $email;



        //validate the password and verify
        if(empty($_POST['password'])){
            trigger_error('Please enter the password', E_USER_WARNING);
            return;
        }
        $password = $_POST['password'];
        if(empty($_POST['verify'])){
            trigger_error('Please enter the verify password', E_USER_WARNING);
            return;
        }
        $verify  = $_POST['verify'];
        if($verify !== $password){
            trigger_error('The password and verify are not matched', E_USER_WARNING);
            return;
        }
        if(strlen($password) < 8 || strlen($password) > 20){
            trigger_error('The length of the password should be between 8 to 20', E_USER_WARNING);
            return;
        }
        if(!preg_match('/[A-Z]+/', $password)){
            trigger_error('The length of the password should have at least one upper character', E_USER_WARNING);
            return;
        }
        if(!preg_match('/[a-z]+/', $password)){
            trigger_error('The length of the password should have at least one lower character', E_USER_WARNING);
            return;
        }
        if(!preg_match('/[0-9]+/', $password)){
            trigger_error('The length of the password should have at least one number', E_USER_WARNING);
            return;
        }



        // check the profile image
        if(empty($_POST['profile']['name'])){
            trigger_error('Please select a profile image', E_USER_WARNING);
            return;
        }
        $aggregateConfig = $this->container->get('aggregateConfig');
        $httpDocs = $aggregateConfig->getConfig('httpDocs');
        $profileFolder  = $aggregateConfig->getConfig('register', 'profile');
        $profileFolder = $httpDocs.$profileFolder;
        if(!file_exists($profileFolder))
            mkdir($profileFolder);
        $imageHandler   = $this->container->get('image');
        $MiMEType   = $imageHandler->getMiMEType($_POST['profile']['tmp_name']);
        if(!$imageHandler->isImage($MiMEType)){
            trigger_error('The file is not an image', E_USER_WARNING);
            return;
        }
        $extension      = $imageHandler->getExtension($MiMEType);
        $profileName    = $imageHandler->getRandomFileName($extension);
        $source         = $_POST['profile']['tmp_name'];
        $destination    = $profileFolder.$profileName;



        // validate the CSRF
        if(empty($_POST['registerCSRF'])){
            trigger_error('the token is missing, Please refresh the form and sign in again', E_USER_WARNING);
            return;
        }
        if(!$this->checkCSRF('registerCSRF',$_POST['registerCSRF'])){
            trigger_error('The form has expired, please refresh the form', E_USER_WARNING);
            return;
        }


        // store into the data
        $query      = "INSERT INTO `user` (`userName`, `userEmail`, `userPassword`, `userImage`) VALUES (?, ?, ?, ?)";
        $data       = Array($username, $email, password_hash($password, PASSWORD_DEFAULT), $profileName);
        $types      = Array('s', 's', 's', 's');
        $database   = $this->getContainer()->get('database');
        if(false === $userID = $database->query($query, $data, $types)){
            trigger_error($database->getDbError(), E_USER_WARNING);
            return;
        }

        // move the file to the folder
        move_uploaded_file($source, $destination);


        trigger_error('Thank you for sign up, you can login in with the credential', E_USER_NOTICE);
        $this->populate = [];
    }


    public function destroy() : void
    {
        if(!session_id())
            session_start();
        $_SESSION = array();
        session_destroy();
    }


    public function CSRFToken(string $identifier) : String
    {
        if(!session_id())
            session_start();
        $token = bin2hex(random_bytes(32));
        $_SESSION[$identifier] = $token;
        return $token;
    }


    public function getPopulatedData(): array
    {
        return $this->populate;
    }


    private function getFingerPrint(string $username, string $email, $token = 'random')
    {
        return hash('sha256', $username.'random111'.$email.'random111'.$token, false);
    }


    public function isAuthorised() : bool
    {
        if(!session_id())
            session_start();

        if(isset($_SESSION['fingerprint'], $_SESSION['username'], $_SESSION['email']))
            return $_SESSION['fingerprint'] === $this->getFingerPrint($_SESSION['username'], $_SESSION['email']);
        return false;
    }


    public function getUserName(): String
    {
        if(!session_id())
            session_start();

        return $_SESSION['username'] ?? '';
    }


    public function getUserID(): int
    {
        if(!session_id())
            session_start();

        return $_SESSION['userID'] ?? 0;
    }
}
