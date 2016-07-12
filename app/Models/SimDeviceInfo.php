<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SimDeviceInfo extends Model {
  /**
  * 关联到模型的数据表
  *
  * @var string
  */
  protected $table = 'simDeviceInfo';
  /**
  * 表明模型是否应该被打上时间戳
  *
  * @var bool
  */
  public $timestamps = false;
  /**
  * 模型日期列的存储格式
  *
  * @var string
  */
  protected $dateFormat = 'U';
  /**
  * 可以被批量赋值的属性.
  *
  * @var array
  */
  protected $fillable = ['iccid'];
}
