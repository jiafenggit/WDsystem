<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    //关联到模型的数据表
//    protected $table = 'Configs' ;
    //主键
    protected $primaryKey = 'key';
    public $incrementing = false;
    //表明模型是否应该被打上时间戳,Eloquent 期望created_at和updated_at已经存在于数据表中
    public $timestamps = true;
    //如果你需要自定义时间戳格式
    protected $dateFormat = 'U';
    //默认情况下，所有的 Eloquent 模型使用应用配置中的默认数据库连接，如果你想要为模型指定不同的连接，可以通过 $connection 属性来设置
//    protected $connection = 'connection-name';
    //可以被批量赋值的属性.
//    protected $fillable = ['name'];
    //不能被批量赋值的属性
//    protected $guarded = ['price'];
    /**
     * @param $key
     * @return string
     * 获取key对应的value值
     */
     public function getValue($key){
        return $this->where($this->primaryKey,$key)->value('value');
    }

    /**
     * @param $key
     * @param $data
     * @return bool
     * 更新或者新增config
     */
    public function saveValue($key,$data){
         //save 方法会先更新，若不存在则插入
         $isset = $this ->where($this->primaryKey,$key)->first();
         if($isset){
             $this->exists =true;
         }
         $this->attributes = array_merge($data,[$this->primaryKey=>$key]);
         $rel = $this->save();

         return $rel;

    }
    public function delrow($key){
        return $this->where($this->primaryKey,$key)->delete();
    }
}
