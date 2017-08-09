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
class FnService extends Controller {
    public $web_cookie = 'web_cookie';
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
    function getWebCookie(){
        $config = new Config();
        $rel = $config->getValue($this->web_cookie);
        if(!empty($rel)){
            return json_decode($rel,true);
        }else{
            return false;
        }
    }


    function saveWebCookie($array){
        $config = new Config();
        $data ['value'] = json_encode($array);
        $data ['type'] ='sys';//系统
        $data ['description'] ='网址cookie';


        $rel = $config->saveValue($this->web_cookie,$data);

        return $rel;
    }

    function delWebCookie(){
        $config = new Config();
        $rel = $config->delrow($this->web_cookie);
        return $rel;
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

}