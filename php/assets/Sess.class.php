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
                unset($_SESSION['error']);
            if($_SESSION['error_i'] = 1)
                $_SESSION['error_i'] = 0;

        }
        if (isset($_SESSION['success_i'])) {
            if($_SESSION['success_i'] == 0)
                unset($_SESSION['success']);
            if($_SESSION['success_i'] == 1)
                ($_SESSION['success_i'] == 0);

        }

    }
    public function run()
    {
        if (strpos($this->path, '?') > 0)
            $this->path = substr($this->path, 0 , strpos($this->path, '?'));
        $this->findPath();
    }

    private function findPath()
    {
        foreach ($this->listPath as $v)
        {
            if($this->prefix . '' . $v->getPage() == $this->path && $this->method == $v->getMethod()){
                $v->execFunction();
                return;
            }
        }
        $this->error404();
    }

    public function error($msg)
    {
        $_SESSION['error'] = $msg;
        $_SESSION['error_i'] = 1;

    }
    public function success($msg)
    {
        $_SESSION['success'] = $msg;
        $_SESSION['success_i'] = 1;

    }

    public function access($access)
    {
        if ($access == 'onlyMember' && !(unserialize($_SESSION['users']) instanceof \ass\mech\Users)){
            $this->error('this page is for members only');
            $this->redirect('/login');

        }
        if ($access == 'onlyGuest' && isset($_SESSION['users']) && (unserialize($_SESSION['users']) instanceof \ass\mech\Users)) {
            $this->error('You are already connected');
            $this->redirect('/');
        }

    }
    private function error404()
    {
        $this->error('are you lost ?');
        $this->redirect('/');
        die;
    }
    public function redirect($url)
    {
        header('Location: ' . $url);
        exit;
    }
    private function addPath($url, $method, $function)
    {
        $p = new Path($url, $method, $function);
        $this->listPath[] = $p;
    }
    public function get($url, $function)
    {
        $this->addPath($url, 'GET', $function);
    }

    public function post($url, $function)
    {
        $this->addPath($url, 'POST', $function);
    }

    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    public function setRoute($route)
    {
        $this->path = $route;
    }

    public function setMethod($method)
    {
        $this->method = $method;
    }

    public function render($view, $array)
    {
        $array['session'] = $_SESSION;
        new Template($array, $view);
    }
}