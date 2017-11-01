<?php
namespace ass\mech;

class Likes
{
    private $id;
    private $people;
    private $selfie;

    public function __construct()
    {

    }
    public function like()
    {
        $selfie = \ass\MAP::getInstance()->findOne('selfie', array('name' =>$this->selfie));
        if ($selfie instanceof Selfie) {
            $this->selfie = $selfie->getId();
           $like = \ass\MAP::getInstance()->findOne('likes', array('people' => $this->people, 'selfie' => $this->selfie));
           if ($like instanceof Likes){
               \ass\MAP::getInstance()->delete('likes', $like->getId());
               return(2);
           } else {
               \ass\MAP::getInstance()->store('likes', get_object_vars($this));
               return(1);
           }
        }
        return (0);
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
}

?>