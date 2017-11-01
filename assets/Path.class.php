<?php

namespace ass;

class Path
{
    private $page;
    private $method;
    private $function;

    public function __construct($page, $method, $function)
    {
        $this->page = $page;
        $this->function = $function;
        $this->method = $method;
    }

    public function executeFunction()
    {
        call_user_func($this->function);
    }

    public function getPage()
    {
        return $this->page;

    }
    public function getMethod()
    {
        return $this->method;
    }

    public function getFunction()
    {
        return $this->function;

    }
}