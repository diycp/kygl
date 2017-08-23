<?php
namespace  app\kygl\validate;

use think\Validate;

class Form1 extends Validate{
    protected $rule=[
        'xmmc|项目名称'=>'require',
        'bh|编号'=>'require',
        'xmlb|项目类别'=>'require',
        'xmlb2|项目类别2'=>'require',
        'fzr|负责人'=>'require',
        'sdate|开始时间'=>'require|dateFormat:Y-m-d',
        'edate|结束时间'=>'require|dateFormat:Y-m-d',
        'htjf|合同经费'=>'require',
        'sjdwjf|实际到位经费'=>'require',
        'lb|类别'=>'require',
        
    ];
}