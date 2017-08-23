<?php
namespace app\kygl\model;
use think\Model;

class Form1 extends Model{
    protected $table = "sys_form_1";

     //关闭自动写入时间戳
    //protected $autoWriteTimestamp = false;

    // 定义自动完成的属性
    protected $insert = ['status' => 1];













}