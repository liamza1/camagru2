<?php

namespace ass;

class Sess
{
    private $listPath = array();
    private $path;
    private $method;
    private $prefix;
    private $html;

    public function  __construct()
    {
        if(isset($_SESSION['errror_i'])){
            if ($_SESSION['errror_i'] = 0)
                unset($_SESSION['errror']);
            if($_SESSION['error_i'] = 1)
                $_SESSION['error_i'] = 0;

        }

    }
}