<?php

namespace App\Http\Controllers\Webs;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Cookie;
use Fn;

class ZhihuWeb extends  Web
{

    public $web_name = 'zhihu.com';
    //驱动类。
    protected $driver;
    public $login_url = 'https://www.zhihu.com/#signin';
    public $home = 'http://www.zhihu.com';



    /**
     * 自动填充账户密码
     */
    public function login()
    {
        $driver = $this->driver;
        $driver->get($this->login_url);
        $driver->wait(10)->until(
            WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(
                WebDriverBy::className('signin-switch-password')
            )
        );
        $password_login = $driver->findElement(WebDriverBy::className('signin-switch-password'))->click();
        //输入账号密码
        $account = Fn::getConfigValue('web_account_'.$this->web_name);
        $password = Fn::getConfigValue('web_password_'.$this->web_name);
        $driver->findElement(WebDriverBy::name('account'))->sendKeys($account);
        $driver->findElement(WebDriverBy::name('password'))->sendKeys($password);
        Fn::shellOutput('已自动填充账户密码','success');

        return ;
    }

    /**
     * @param $cookies
     * 有cookie的时候，自动的登录
     */
    public function loginByCookie($cookies){

        $driver = $this->driver;
        Fn::shellOutput('已有cookie');
//            dump($cookies);
//             adding cookie
        $driver->get($this->home);
        $driver->manage()->deleteAllCookies();
        foreach ($cookies as $key =>$row){
            $cookie_row = Cookie::createFromArray($row);
            $driver->manage()->addCookie($cookie_row);
        }
        Fn::shellOutput('添加cookie成功，即将进入网站','success');
        $driver->get($this->home);
    }

    /**
     * 点击登录
     */
    public function loginByVerify(){

        $driver = $this->driver;
        Fn::shellOutput('已手动操作登录');
        //必须是登录的网址
        $driver->findElement(WebDriverBy::cssSelector('button.submit[type=submit]'))->click();

    }

}
