<?php

namespace App\Http\Controllers;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Cookie;
use Fn;



/**
 * Class Webdriver
 * @package App\Http\Controllers
 * 系统启动入口类
 */
class Webdriver extends Controller
{
    function __construct()
    {


    }

    /**
     * 启动系统
     *
     * @return Response
     */
    public function run()
    {

        //通过selenium + geckodirver 驱动本地firefox
        //在本目录下执行以下命令
        //java -Dwebdriver.gecko.driver = "/Users/zhangzhi/PhpstormProjects/php-webdriver/php-webdriver/geckodriver" -jar selenium-server-standalone-3.4.0.jar
        //java  -jar selenium-server-standalone-3.4.0.jar

        $host = 'http://localhost:4444/wd/hub/';//用于启动selenium，只支持firefox
        $capabilities = DesiredCapabilities::firefox();
        // start Firefox with 5 second timeout
        $driver = RemoteWebDriver::create($host, $capabilities, 5000);
        //查询是否已经有cookie，若有，不进行登录操作。
        $cookies= Fn::getWebCookie();
        if(!$cookies){
            $driver->get('https://www.zhihu.com/#signin');
            $driver->wait(10)->until(
                WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(
                    WebDriverBy::className('signin-switch-password')
                )
            );
            $password_login = $driver->findElement(WebDriverBy::className('signin-switch-password'))->click();
            //输入账号密码
            $account = Fn::getConfigValue('web_account');
            $password = Fn::getConfigValue('web_password');
            $driver->findElement(WebDriverBy::name('account'))->sendKeys($account);
            $driver->findElement(WebDriverBy::name('password'))->sendKeys($password);
            Fn::shellOutput('已自动填充账户密码','success');
        }else{
            Fn::shellOutput('已有cookie');

//            dump($cookies);
//             adding cookie
            $driver->get('https://www.zhihu.com');
            $driver->manage()->deleteAllCookies();

            foreach ($cookies as $key =>$row){
                $cookie_row = Cookie::createFromArray($row);

                $driver->manage()->addCookie($cookie_row);
            }
            Fn::shellOutput('添加cookie成功，即将进入网站','success');

            $driver->get('https://www.zhihu.com');


        }

        //命令集合
        $adv='';//优先执行的命令
        goto cmd;
        cmd:{
        Fn::shellOutput('等待命令：');

        $strin = $adv?:fread(STDIN,1000);
        $strin = trim($strin);
        switch ((string)$strin){
            case 'login':
                Fn::shellOutput('已手动操作登录');
                //必须是登录的网址
                $driver->findElement(WebDriverBy::cssSelector('button.submit[type=submit]'))->click();
                sleep(2);
                $cookies = $driver->manage()->getCookies();
                //保存cookie，保存标识。下次启动直接调用cookie
                foreach ($cookies as $row){
                    $cookies_data[] = $row->toArray();
                }

                $save = Fn::saveWebCookie($cookies_data);
                if(!$save){
                    Fn::shellOupt('保持cookie失败','fail');
                }
                break;
            case 'exit':
                $driver->quit();
                break ;
            case 'delcookie':
                Fn::delWebCookie();
                $adv='exit';
                break;
            default:
                echo "不合法命令\n";
                break;

        }
        if ($strin != 'exit') goto cmd;
    }






















        //print_r($cookies);
        // click the link 'About'
        //$link = $driver->findElement(
        //    WebDriverBy::id('sumbit')
        //);
        //$link->click();
        // wait until the page is loaded
        //$driver->wait()->until(
        //    WebDriverExpectedCondition::titleContains('About')
        //);

        // write 'php' in the search box
        //$driver->findElement(WebDriverBy::id('input'))
        //    ->sendKeys('php');
        // submit the form
        //$driver->findElement(WebDriverBy::id('submit'))
        //    ->click(); // submit() does not work in Selenium 3 because of bug https://github.com/SeleniumHQ/selenium/issues/3398
        // wait at most 10 seconds until at least one result is shown
        //$driver->wait(10)->until(
        //    WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(
        //        WebDriverBy::className('gsc-result')
        //    )
        //);
        //$driver->wait()->until(
        //    WebDriverExpectedCondition::titleContains('subm')
        //);


    }








}

