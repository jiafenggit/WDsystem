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
     * 1.找到股票代码。 60条
     * 2.进入每一条代码。
     * 3.点击研报，找寻合适的评论，提交评论
     */
    public function main(){
        $driver = $this->driver;
        $elements = $driver->findElements(WebDriverBy::cssSelector('ul.my_zxg_d>li:first-child>a,ul.my_zxg_u>li:first-child>a'));
//        dump(count($elements));
        $main = $driver->getWindowHandle();
        dump($main);

        foreach ($elements as $key => $element) {


            $goto = $element->click();
            //这次操作后，新生成一个新的窗口，$driver 在不切换窗口的情况下能检测到新窗口的元素
            $driver->wait(10)->until(
                WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(
                    WebDriverBy::id('v2_publishIpt')
                )
            );
            //now can iterate through array to get desired handle
            $handles = $driver->getWindowHandles();
//            dump($handles);
            $driver->switchTo()->window(
                end($handles)
            );
            //加载界面后，需要延迟几秒，保证节点能够切换过去，保证能找到元素
            sleep(2);
            //在新窗口找出元素，操作元素，需要保证已经切换到新窗口内。配合sleep（2）
            $textarea = $driver->findElement(WebDriverBy::id('v2_publishIpt'));
            $content = $this->getContent();
            $textarea->clear()->sendKeys($content);
            $driver->findElement(WebDriverBy::id('pbBtn'))->click();
            sleep(3);
            $driver->close();
            $driver->switchTo()->window(
               $main
            );
            if($key  == 1){
                break;
            }

        }



    }


    function getContent(){
        
        $data = ['能不能涨停一次','快涨快涨啊！！','怎么走成这样','能不能做个T？'];
        $rand = rand(0,3);
        return $data[$rand];

    }











   function jsInsert(){

        $text='123php';
        $js = "console.log(123);var sum=document.getElementById('v2_publishIpt'); sum.value='" .$text . "';";
        $js = "console.log(123);";
        dump($js);
//        $driver->executeScript($js);

   }


}
