<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverBy;


class Webdriver extends Controller
{
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

        $driver->get('https://www.zhihu.com/#signin');

        $driver->wait(10)->until(
            WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(
                WebDriverBy::className('signin-switch-password')
            )
        );
        $password_login = $driver->findElement(WebDriverBy::className('signin-switch-password'))->click();
        //输入账号密码
        $account = "583471388@qq.com";
        $password = "aaa1101990";
        $driver->findElement(WebDriverBy::name('account'))->sendKeys($account);
        $driver->findElement(WebDriverBy::name('password'))->sendKeys($password);

        // adding cookie
        //$driver->manage()->deleteAllCookies();
        //$cookie = new Cookie('cookie_name', 'cookie_value');
        //$driver->manage()->addCookie($cookie);
        //$cookies = $driver->manage()->getCookies();

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
        goto cmd;
        cmd:{
            $strout = fwrite(STDOUT,'请输入命令:');
            $strin = fread(STDIN,1000);

            $strin = trim($strin);
            switch ((string)$strin){
                case 'login':
                    echo '已手动操作登录';
                    $driver->findElement(WebDriverBy::cssSelector('button.submit[type=submit]'))->click();
                    sleep(2);
                    $cookies = $driver->manage()->getCookies();
                    print_r($cookies);
                    //保存cookie，保存标识。下次启动直接调用cookie

                    break;
                case 'exit':
                    $driver->quit();
                    break ;
                default:
                    echo "不合法命令\n";
                    break;

            }
            if ($strin != 'exit') goto cmd;
        }


        }
}

