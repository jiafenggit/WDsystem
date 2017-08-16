<?php
/**
 * Created by PhpStorm.
 * User: zhangzhi
 * Date: 17/8/8
 * Time: 14:41
 */
namespace App\Services;
use App\Http\Controllers\Controller;
use App\Config;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverBy;
class FnService extends Controller {
    public $web_cookie = 'web_cookie_';
//没必要注入，config只是个一张表
//    function __construct(Config $config)
//    {
//        $this->config_model = $config;
//    }

    function getConfigValue($key){
        $config = new Config();
        $rel = $config->getValue($key);
        if(!empty($rel)){
            return $rel;
        }else{
            return false;
        }
    }
    /**
     * 获取是否已经存储过cookie
     */
    function getWebCookie($key){
        $config = new Config();
        $rel = $config->getValue($this->web_cookie.$key);
        if(!empty($rel)){
            return json_decode($rel,true);
        }else{
            return false;
        }
    }

    /**
     * @param $key
     * @param $array
     * @return bool
     * 保存cookie
     */
    function saveWebCookie($key,$array){
        $config = new Config();
        $data ['value'] = json_encode($array);
        $data ['type'] ='sys';//系统
        $data ['description'] ='网址cookie';


        $rel = $config->saveValue($this->web_cookie.$key,$data);

        return $rel;
    }

    /**
     * @param $key
     * @return bool|null
     * 删除cookie
     */
    function delWebCookie($key){
        $config = new Config();
        $rel = $config->delrow($this->web_cookie.$key);
        return $rel;
    }

    /**
     * @param $driver
     * @Param $target 目标窗口
     * @param $endMark 目标窗口标识 目标窗口已存在的话，无需在判断 即标识为空
     * 切换窗口，统一使用该函数，保证切换窗口后，driver已成功切换。
     */
    function switchWin($driver,$target='next',$endMark=''){
        if(!$target || $target == 'next'){
            $handles = $driver->getWindowHandles();
            $target = end($handles);
        }
        $driver->switchTo()->window(
            $target
        );
        if($endMark) {
            $driver->wait(30)->until(
                WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(
                    WebDriverBy::id($endMark)
                )
            );
        }
    }

    /**
     * @param $txt
     * @param string $status
     * shell 终端输出，区分多种状态
     */
    function shellOutput($txt,$status=''){

         $out = "";
         switch($status) {
          case "success":
           $out = "[42m"; //Green background
           break;
          case "fail":
           $out = "[41m"; //Red background
           break;
          case "warning":
           $out = "[43m"; //Yellow background
           break;
          case "note":
           $out = "[44m"; //Blue background
           break;
          default:
            $out = "[47m";
         }
        echo chr(27) . "$out" . "$txt" . chr(27) . "[0m\n";

    }

    /**
     * @param $driver
     * @param $mark
     * 方便调试使用，终端输出当前窗口，和所有窗口
     */
    function logAllWindows($driver,$mark){
        if(@DEBUG==true){}
            $handle = $driver->getWindowHandle();
            $handles = $driver->getWindowHandles();
            dump("当前窗口_$mark:");
            dump($handle);
            dump("所有窗口_$mark:");
            dump($handles);
    }

}


