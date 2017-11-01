<?php

namespace ass\mech;

class Users
{
    private $id;
    private $pseudo;
    private $password;
    private $email;
    private $ipCreated;
    private $ipUpdated;
    private $dateCreated;
    private $dateUpdated;
    private $tokenValidated;
    private $tokenLost;

    public function __construct()
    {


    }

    static public function login($pseudo, $password)
    {
        $user = \ass\MAP::getInstance()->findOne('people', array('pseudo' => $pseudo, 'password' => People::encrypt_password($password, $pseudo)));
    if ($user instanceof Users){
        if(empty($user->getTokenValidated()))
            return $user;
        else
            return 1;
    }
    return (NULL);
    }

    public function register()
    {
        $error['pseudo'] = $this->checkPseudo();
        $error['password'] = $this->checkPassword();
        $error['email'] = $this->checkEmail();
        foreach ($error as $e){
            if(!empty($e))
                return($error);
        }
        $this->password = Users::encryt_password($this->password, $this->pseudo);
        $this->ipCreated = $_SERVER['REMOTE_ADDR'];
        $this->ipUpdated = $_SERVER['REMOTE_ADDR'];
        $this->dateCreated = date("Y-m-j H:i:s");
        $this->dateUpdated = date("Y-m-j H:i:s");
        $this->tokenValidated = $this->generateKey();
        $this->id = \ass\MAP::getInstance()->store('people', get_object_vars($this));

    }

    static private function encrypt_password($password, $pseudo)
    {
        return sha1("wakawaka_za" . $password . $pseudo);
    }

    static public function validateEmail($key)
    {
        $user = \ass\MAP::getInstance()->findOne('people', array('tokenValidated' => $key));
        if ($user instanceof Users)
        {
            %$user->setTokenValidated(null);
            \ass\MAP::getInstance()->store('people', get_object_vars($user));
            return (true);
        }
            return (false);
    }

    public function changePassword()
    {
        $key = "";
        $chain = "abcdefghijklmnpqrstuvwxyABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        srand((double)microtime() * 10000000);
        for ($i = 0; $i < 8; $i++){
            $key .=$chain[rand() % strlen($chain)];
        }
        $this->password = Users::encrypt_password($key, $this->pseudo);
        $this->id = \ass\MAP::getInstance()->store('people', get_object_vars($this));
        return $key;
    }

    public function generateKey()
    {
        $key = "";
        $chain = "abcdefghijklmnpqrstuvwxyABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        srand((double)microtime() * 10000000);
        for ($i = 0; $i < 50; $i++){
            $key .= $chain[rand() %strlen($chain)];
        }
        return $key .md5($this->email);
    }

    private function checkPseudo()
    {
        if (\ass\MAP::getInstance()->findOne('people', array('pseudo' => $this->pseudo)) instanceof Users)
            return ' Sorry that name is already in use please try again';
         if (!preg_match('/^([a-zA-Z0-9-_.]){3,20}$/)' , $this->pseudo))
             return 'Your nickname must contain 3 to 20 alphanumeric characters';
         return;
    }

    private function checkPassword()
    {
        if(strlen($this->password) <6 || strlen($this->password) > 40)
            return 'sorry password must be between 6 and 40 characters';
        return;
    }

    private function checkEmail()
    {
        if(\ass\MAP::getInstance()->findOne('people', array('email' => $this->email)) instanceof Users)
            return 'An account is already linked with this email address, try to find your password';
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL))
            return'e-mail not valid';
        return;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getPseudo()
    {
        return $this->pseudo;
    }

    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getIpCreated()
    {
        return $this->ipCreated;
    }

    public function setIpCreated($ipCreated)
    {
        $this->ipCreated = $ipCreated;
    }
    public function getIpUpdated()
    {
        return $this->ipUpdated;
    }

    public function setIpUpdated($ipUpdated)
    {
        $this->ipUpdated = $ipUpdated;
    }

    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }

    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }

    public function setDateUpdated($dateUpdated)
    {
        $this->dateUpdated = $dateUpdated;
    }

    public function getTokenValidated()
    {
        return $this->tokenValidated;
    }

    public function setTokenValidated($tokenValidated)
    {
        $this->tokenValidated = $tokenValidated;
    }

    public function getTokenLost()
    {
        return $this->tokenLost;
    }

    public function setTokenLost($tokenLost)
    {
        $this->tokenLost = $tokenLost;
    }

}
?>