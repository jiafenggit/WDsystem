<?php

namespace App\Http\Controllers;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Cookie;

use Facebook\WebDriver\WebDriverKeys;
use Fn;
use App;




/**
 * Class Webdriver
 * @package App\Http\Controllers
 * 系统启动入口类
 */
class Webdriver extends Controller
{
//    function __construct(web $web)
//    {
//
//        $this->web = $web;
//
//    }
    //下一次需要执行的命令
    public $adv = '';
    /**
     * 启动系统
     *
     * @return Response
     * 通过selenium + geckodirver 驱动本地firefox
     * 在public目录下执行以下命令
     * java  -jar selenium-server-standalone-3.4.0.jar
     * java -Dwebdriver.gecko.driver = "/Users/zhangzhi/PhpstormProjects/php-webdriver/php-webdriver/geckodriver" -jar selenium-server-standalone-3.4.0.jar
     */
    public function run($id)
    {
        set_time_limit(0);
        $host = 'http://localhost:4444/wd/hub/';//用于启动selenium，只支持firefox
        $capabilities = DesiredCapabilities::firefox();
        // start Firefox with 5 second timeout
        $driver = RemoteWebDriver::create($host, $capabilities, 5000);

        //查询是否已经有cookie，若有，不进行登录操作。
        $web = App::makeWith('App\Http\Controller\Webs\Web',['id'=>$id]);
        $web->setDriver($driver);
        $web_name = $web->getWebName();

        $cookies= Fn::getWebCookie($web_name);
        if(!$cookies){
            $cmd = $web->login();
            if($cmd){
                $this->setAdv($cmd);
            }
        }else{
           $web->loginByCookie($cookies);
           $this->setAdv('main');

        }

        //优先执行的命令



        //命令集合
        goto cmd;
        cmd:{

        if(!$this->adv){
            Fn::shellOutput('等待命令：');
        }
        $strin = $this->adv?:fread(STDIN,1000);
        $strin = trim($strin);
        //清空命令
        $this->delAdv();
        switch ((string)$strin){
            case 'login':
                //如果需要验证，可在该函数内完成，并且点击登录
                $web->loginByVerify();
                sleep(2);
                $cookies = $driver->manage()->getCookies();
                //保存cookie，保存标识。下次启动直接调用cookie

                foreach ($cookies as $row){

                    $arr = $row->toArray();
                    if($arr['name'] == 'user_id'){
                        $arr['domain'] = trim($arr['domain'],'.');
                    }
                    $cookies_data[] = $arr;
                }
                $save = Fn::saveWebCookie($web_name,$cookies_data);
                if(!$save){
                    Fn::shellOupt('保持cookie失败','fail');
                }

                break;
            case 'exit':
                $driver->quit();
                break ;
            case 'delcookie':
                Fn::delWebCookie($web_name);
                $this->setAdv('exit');

                break;

            case 'main' :
                $web->main();
//                $this->setAdv('exit');
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



     public function setAdv($adv){
        $this->adv =$adv;
    }
     public function delAdv(){
         $this->adv = '';
     }




}

