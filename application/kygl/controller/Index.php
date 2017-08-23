<?php
namespace app\kygl\controller;

use think\Controller;
use think\Session;

class Index extends Controller
{
     //登录前的权限验证代码
    public $beforeActionList = ['checkLogins'];

    //控制器操作前进行权限验证
    public function checkLogins()
    {
        if(Session::get('username') == ''){
            $this->redirect('user/login');
        }

    }

    public function index()
    {
        echo $this->fetch('');
    }
}
