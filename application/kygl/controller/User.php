<?php
namespace app\kygl\controller;
use app\kygl\model\Form1;
use think\Controller;
use think\Session;
use think\model;
use think\Db;





//use \app\kygl\model\User;
class User extends Controller
{
    public function login(){
        
        //$this->assign();
        //$myy=new Controller();

        //return $this->fetch();
        //$object=new Controller;
        return $this->fetch('');

    }

    public function checkLogin(){


           //dump($_POST);exit;

        $username = $_POST['username'];
        $password = $_POST['password'];
        //$id = $_POST['id'];
        //$usergroup = $_POST['usergroup'];
        //$user = new \app\kygl\model\User;
        $user =model('user');
        $flag = $user->get(['username'=>$username,'password'=>$password]);
        //dump($flag);exit;
        if($flag){
            Session::set('uid',$flag['id']);
            Session::set('username',$username);
            Session::set('usergroup',$flag['usergroup']);
            $this->redirect('index/index');
    }else{
            //跳转回去重新登录
            $this->redirect('user/login','status=error');
        }
    }

    public function logout(){
        Session::clear();
        Session::destroy();
        //跳转回登录页面
        $this->redirect('user/login','status=logout');
    }

    public function chpassword(){
        return $this->fetch('user/chpassword');
    }

//修改登录密码
public function chpasswordaction(){

//dump($_POST);exit;
$us1=$_POST['userpassword'];
$us2=$_POST['userpassword2'];
//获取session下的uid
$id=(Session('uid'));
//dump($us1);dump($us2);exit;
//比对两次输入的密码是否一致
if($us1==$us2){
    //实例化父类模型,并且关联user表
$user =Model('user');
//更新数据库密码
$pwd=$user->update(['id' => $id, 'password' => $_POST['userpassword']]);
//dump($pwd);exit;
//判断返回值

if($pwd)
    {
        $this->success('修改密码成功!','index/index');
    

    }

}
else

{
$this->error('两次设置的密码不一致,请重新设置！');
}





}
public function Form1(){

$name=Db::query("select a.*,b.*,c.* from sys_user a,sys_form_1 b,sys_form_2 c where a.id=b.uid ");


dump($name);exit;


}
}