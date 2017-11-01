<?php
namespace ass\mech;

class Comment
{
    private $id;
    private $people;
    private $selfie;
    private $message;

    public function __construct()
    {

    }

    public function send()
    {
        $selfie = \ass\MAP::getInstance()->findOne('selfie' , array('id' => $this->selfie));
        if ($selfie instanceof Selfie)
        {
            $this->selfie = $selfie->getId();
            \ass\MAP::getInstance()->findOne('comment', get_object_vars($this));
        }
        return (0);
    }

    public function checkMessage()
    {
        $this->message = trim($this->message);
        $this->message = htmlentities($this->message);
        if (strlen($this->message) <= 0 || strlen($this->message) > 1000)
        {
            return 'Sorry message must be between 1 and 1000 characters';
        }
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

    public function getPeople()
    {
        return $this->people;
    }

    public function setPeople($people)
    {
        $this->people = $people;
    }

    public function getSelfie()
    {
        return $this->selfie;
    }

    public function setSelfie($selfie)
    {
        $this->selfie = $selfie;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }
}
?>