<?php

namespace App\Http\Controllers\Webs;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Cookie;
use Facebook\WebDriver\WebDriverKeys;
use Fn;

class AiGuPiao extends  Web
{

    public $web_name = '51gupiao.com';
    //驱动类。
    protected $driver;
    public $login_url = 'https://www.5igupiao.com/user/hotstock.php';
    public $home = 'https://www.5igupiao.com/user/hotstock.php';

    //


    /**
     * 自动填充账户密码
     */
    public function login()
    {
        $driver = $this->driver;
        $driver->get($this->login_url);
        $driver->wait(10)->until(
            WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(
                WebDriverBy::id('login_btn')
            )
        );
        $password_login = $driver->findElement(WebDriverBy::id('login_btn'))->click();
        //输入账号密码
        $account = Fn::getConfigValue('web_account_'.$this->web_name);
        $password = Fn::getConfigValue('web_password_'.$this->web_name);
        $driver->findElement(WebDriverBy::id('log_name'))->sendKeys($account);
        $driver->findElement(WebDriverBy::id('log_pwd'))->sendKeys($password);
        Fn::shellOutput('已自动填充账户密码','success');

        return 'login';
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
        Fn::shellOutput('自动登录中');
        //必须是登录的网址
        $driver->findElement(WebDriverBy::id('log_btn'))->click();

    }

    /**
     * 主入口。
     */
    public function main(){
        $driver = $this->driver;
        $current_handle = $driver->getWindowHandle();
        $handles = $driver->getWindowHandles();
        dump($current_handle);
        dump($handles);

        // using the browser shortcut to create a new tab
//        $driver->getKeyboard()->sendKeys(
//            array(WebDriverKeys::CONTROL, 't')
//        );
//
//        // using the browser shortcut to create a new window
//        $driver->getKeyboard()->sendKeys(
//            array(WebDriverKeys::CONTROL, 'n')
//        );
//        $driver->switchTo()->window(
//            end($driver->getWindowHandles())
//        );

    }



}
