<?php
/**
 * Created by PhpStorm.
 * User: zhangzhi
 * Date: 17/8/8
 * Time: 14:07
 */
namespace App\Facades;
use Illuminate\Support\Facades\Facade;
class FnFacade extends Facade{

    protected static function getFacadeAccessor() {
        return 'Fn';
    }
}
