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
    public $home_mark ="my_zxg";
    //主窗口
    public $main;

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
        $count = (count($elements));
        $this->main = $driver->getWindowHandle();
//        dump('主窗口:'.$this->main);

        $offset = 60;
        $length = 5;
        $elements = array_slice($elements,$offset,$length);
        Fn::shellOutput("待评价股票数$count ,从$offset 条开始至之后的 $length 条",'success');
        $i = 0;
        foreach ($elements as $key => $element) {

            $code = $element->getText();
            $content = $this->getContent($code);
            if(empty($content)) continue;
            $goto = $element->click();
            //这次操作后，新生成一个新的窗口，$driver 在不切换窗口的情况下能检测到新窗口的元素，并且等待加载
            $driver->wait(30)->until(
                WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(
                    WebDriverBy::id('pbBtn')
                )
            );
            Fn::switchWin($driver,'next','pbBtn');
            Fn::logAllWindows($driver,'打开评论界面');
            $textarea = $driver->findElement(WebDriverBy::id('v2_publishIpt'));
            $textarea->clear()->sendKeys($content);
            sleep(3);
            $driver->findElement(WebDriverBy::id('pbBtn'))->click();
            sleep(3);
            $driver->close();
            Fn::switchWin($driver,$this->main,'');
            Fn::logAllWindows($driver,'关闭评论界面');
            $i++;

        }
        Fn::shellOutput("成功评论条数$i",'success');


    }


    function getContent($code){
        $driver = $this->driver;
        $js="window.open('http://guba.eastmoney.com/list,$code.html')";
        $driver->executeScript($js);
        Fn::switchWin($driver,'next','mainbody');
        Fn::logAllWindows($driver,'打开内容页');
        $elements = $driver->findElements(WebDriverBy::cssSelector('div.articleh>span:nth-child(3)>a'));
//        dump(count($elements));
        if(count($elements) >=25){
            $rand = rand(15,25);
            $data = $elements[$rand]->getAttribute('title');
        }else{
            $data = '';
        }

        Fn::shellOutput($data);

        $driver->close();
        Fn::switchWin($driver,$this->main,'');
        Fn::logAllWindows($driver,'关闭内容页');

        return $data;

    }










}
