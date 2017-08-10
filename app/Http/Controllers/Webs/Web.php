<?php

namespace App\Http\Controllers\Webs;

use App\Http\Controllers\Controller;

abstract class Web extends Controller
{
    public $web_name = 'none';
    //驱动类。
    protected $driver;
    public $login_url = '';
    public $home = '';

    public function setDriver( $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @return string
     * 返回当前调用的webName
     */
    function getWebName(){
        return $this->web_name;
    }

    abstract function login();
    abstract function loginByCookie($cookies);
    abstract function loginByVerify();

}
