<?php
namespace app\kygl\controller;

use app\kygl\model\Form1;
use app\kygl\model\Form2;
use think\Controller;
use think\Model;
use think\Request;
use think\Session;
use think\Db;

class Keyan extends Controller {
    //在线申报的索引页面
    public function index(){
        $xiangmu_model = model('xiangmu');
        $xiangmus=$xiangmu_model->all();
        $this->assign('xiangmus',$xiangmus);
        return $this->fetch('index');
    }

    //在线申报的表单页面
    public function shenbao(){
        /*$xiangmu_model = model('xiangmu');
        $xiangmu = $xiangmu_model->get(['id'=>$xmid]);
        $this->assign('xiangmu',$xiangmu);
        return $this->fetch('keyan/shenbao/form'.$xmid);*/

          $id=$_GET['xmid'];
          //dump($id);exit;
          $model=model('xiangmu');
          $xiangmu=$model->get(['id'=>$id]);
          //dump($xiangmu);exit;
          $this->assign('xiangmu',$xiangmu);
          echo $this->fetch('keyan/shenbao/form'.$id);
}

    //在线申报请求处理
    public function shenbaoActionform1()
    {

        //验证字段是否正确
        $validate = validate('Form1');
        if (!$validate->check($_POST)) {
            $msg = $validate->getError();
            $this->assign('errmsg', $msg);
            return $this->fetch('keyan/result_error');
        } else {
            //验证图片并上传
            $pfwj = request()->file('pfwj');
            $rws = request()->file('rws');
            //var_dump($pfwj->checkExt(['jpg','png','gif']));var_dump($rws->checkExt(['jpg','png','gif']));exit();
            if ($pfwj and $pfwj->checkExt(['jpg','png','gif'])) {
                // 移动到框架应用根目录/public/upload/ 目录下
                $pfwjInfo = $pfwj->rule('uniqid')->move(ROOT_PATH . 'public' . DS . 'upload');
                //$json=json_encode($pfwjInfo,true);
                //dump($json);exit;
            
                
                
                //$array=toArray($pfwjInfo);
                
                if ($pfwj->getError())
                 {
                    $msg = $pfwj->getError();
                    $this->assign('errmsg', $msg);
                    return $this->fetch('keyan/result_error');
                }
            } else {
                $msg = "图片格式错误！";
                $this->assign('errmsg', $msg);
                return $this->fetch('keyan/result_error');
            }

            if ($rws and $rws->checkExt(['jpg','png','gif'])) {
                // 移动到框架应用根目录/public/upload/ 目录下
                $rwsInfo = $rws->rule('uniqid')->move(ROOT_PATH . 'public' . DS . 'upload');
                //dump($rws->getError());exit;
                
               if ($rws->getError()) {
                    $msg = $rws->getError();
                    $this->assign('errmsg', $msg);
                    return $this->fetch('keyan/result_error');
                }
            } else {
                $msg = "图片格式错误！";
                $this->assign('errmsg', $msg);
                return $this->fetch('keyan/result_error');
            }
          //dump(session('uid'));exit;
            //正确，写入数据库
            $form1_model = new Form1($_POST);

            $form1_model->pfwj = $pfwjInfo->getSavename();
            $form1_model->rws = $rwsInfo->getSavename();

            $form1_model->uid = Session::get('uid');
            $form1_model->allowField(true)->save();
            $msg='提交成功!';
            $this->redirect('keyan/result_success','msg='.$msg);
        }
    }




public function shenbaoActionForm2(){
               
   //dump($_POST);exit;
   $validate=Validate('Form2');
   if(!$validate->check($_POST)){
    $errmsg=$validate->getError();
    $this->assign('errmsg',$errmsg);
    echo $this->fetch('keyan/result_error');

   }
else{
$pfwj=request()->file('pfwj');
$rws=request()->file('rws');
//dump($pfwj);exit;

if($pfwj->checkExt(['jpg','png','gif'])){

$return=$pfwj->rule('uniqid')->move(ROOT_PATH . 'public' . DS . 'upload');

if ($pfwj->getError())
{
$msg = $pfwj->getError();
$this->assign('errmsg', $msg);
return $this->fetch('keyan/result_error');}

}else{

  $this->error('上传文件格式不正确');

}

if($rws->checkExt(['jpg','png','gif'])){

$return2=$rws->rule('uniqid')->move(ROOT_PATH . 'public' . DS . 'upload');

if ($rws->getError())
{
$msg2 = $rws->getError();
$this->assign('msg2', $msg2);
return $this->fetch('keyan/result_error');}

}else{

  $this->error('上传文件格式不正确');

}

 $form1_model = new Form2($_POST);

$form1_model->pfwj = $return->getSavename();
$form1_model->rws = $return2->getSavename();

$form1_model->uid = Session::get('uid');
$form1_model->allowField(true)->save();
$msg='提交成功!';
$this->redirect('keyan/result_success','msg='.$msg);


}
}

    //提交成功的提示信息
    public function result_success(){
            $this->assign('msg',htmlspecialchars($_GET['msg']));
            return $this->fetch('keyan/result_success');
    }

    //查询进度的首页
    public function jindu(){
        $xiangmu_model = model('xiangmu');
        $xiangmus=$xiangmu_model->all();
        $this->assign('xiangmus',$xiangmus);
        return $this->fetch('jindu');
    }

    //进度查询列表
    public function jinduchaxun(){
        /*$xiangmu_model = model('xiangmu');
        $xiangmu = $xiangmu_model->get(['id'=>$xmid]);
        $this->assign('xiangmu',$xiangmu);*/
        $xmid=$_GET['xmid'];
        $xiangmu_model = model('xiangmu');
        $xiangmu=$xiangmu_model->get(['id'=>$xmid]);
        $this->assign('xiangmu',$xiangmu);
        $form = model('form'.$xmid);
        /*$data = $form->all(function($query){
            $query->where('uid',Session::get('uid'))->order('update_time','desc');
        });*/
  $data=Db::query("select a.username,b.* from sys_user a,sys_form_$xmid b where a.id=b.uid order by update_time desc");
        /*$data=$form->all(function($query){$query->order('update_time','desc');

        });*/
        //dump($data);exit;
        $this->assign('data',$data);
        return $this->fetch('keyan/jindu/table'.$xmid);
         //$id=$_GET['xmid'];



}

//后台admin审核首页
public function shenhe(){

$shenhe_model=model('xiangmu');
$shenhe=$shenhe_model->all();
//dump($shenhe);exit;
//dump($page);exit;
$this->assign('shenhe',$shenhe);
echo $this->fetch('keyan/shenhe');




}


//后台admin显示申报情况并且进行审核
public function shenhechaxun(){


$id=$_GET['xmid'];
//dump($id);exit;

$xiangmu=model('xiangmu');
$xiangmus=$xiangmu->get(['id'=>$id]);
//dump($xiangmus);exit;
$this->assign('xiangmus',$xiangmus);

$shenhechaxun=model('Form'.$id);
$name=Db::query("select a.username,b.* from sys_user a,sys_form_$id b where a.id=b.uid order by update_time desc");
$obj=Db::query("select id from sys_user");
//dump($obj);exit;
//dump($name);exit;
/*$name=$names->all(function($query){$query->order('update_time','desc');


});*/
//$name=$names->order('update_time,desc');
//dump($name);exit;
$this->assign('name',$name);
     
echo $this->fetch('keyan/shenhe/shenhe'.$id);
}


//status字段,进行是否审核判断
public function shjg(){

$id=$_GET['id'];
$form=$_GET['form'];
$obj1=model('xiangmu');
//dump($obj1);exit;
$xiangmu=$obj1->get(['id'=>$form]);
$this->assign('xiangmu',$xiangmu);
//dump($xiangmu);exit;
//$obj=$_POST['id'];
$modify=model('Form'.$form);

$data=$modify->get(['id'=>$id]);
//dump($data);exit;
$this->assign('data',$data);
return $this->fetch('keyan/shjg');

}



//admin进行审核
public function xiugaistatus(){
//dump($_POST['id']);exit;
//dump($_POST);exit;
$id=$_GET['form'];
//dump($id);exit;
$modify=model('Form'.$id);
$res=$modify->where('id',$_POST['id'])->update(['status' => $_POST['status'],'yuanyin'=>$_POST['yuanyin']]);
//dump($res);exit;
if($res){


   $this->success('恭喜,审核成功!','keyan/shenhe');
}

else{$this->error('请勿提交重复的审核结果!');

}

}
//admin 统计 笨方法一个个去实例化传递的  - -
/*public function number(){
$index=model('xiangmu');
$shuliang=$index->count();
$formone=model('form1');
$numberone=$formone->count();
$sum=$shuliang+$numberone;
$this->assign(['shuliang'=>$shuliang,
                'numberone'=>$numberone,

                                   ]);


    echo $this->fetch('keyan/num');}*/
//修改自己提交的项目
public function xiugai(){
$id=session('uid');
$model=model('xiangmu');
$search=$model->all();
/*echo"<pre>";
print_r($search);
echo"</pre>";*/
$this->assign('search',$search);
echo   $this->fetch('xiugai');
}




//具体修改哪一个分类下的项目
public function xiugaixm(){

$id=$_GET['xmid'];
//var_dump($id);
$uid=session('uid');
$data=Db::query("select * from sys_form_$id where uid = $uid");
$this->assign('data',$data);
$this->assign('id',$id);
echo $this->fetch('keyan/xiugai/xiugai'.$id);
}



public function xg(){

$num=$_GET['xmid'];
$id=$_GET['id'];
$dat=Db::query("select * from sys_form_$num where id = $id");
/*echo"<pre>";
print_r($dat);
echo"</pre>";*/
$this->assign('dat',$dat);
$this->assign('id',$id);
echo $this->fetch('keyan/xg/xg'.$num);

}

 public function xgwc1()
    {
           $id=$_GET['id'];
            /*$xmmc=$_POST['xmmc'];
              $bh=$_POST['bh'];
                $bz=$_POST['bz'];
                $fzr=$_POST['fzr'];
                $htjf=$_POST['htjf'];
             $sjdwjf=$_POST['sjdwjf'];
             $lb=$_POST['lb'];*/
             //dump($_POST);exit;//验证字段是否正确
        $validate = validate('Form1');
        if (!$validate->check($_POST)) {
            $msg = $validate->getError();
            $this->assign('errmsg', $msg);
            return $this->fetch('keyan/result_error');
        } else {
            //验证图片并上传
            $pfwj = request()->file('pfwj');
            $rws = request()->file('rws');
            //var_dump($pfwj->checkExt(['jpg','png','gif']));var_dump($rws->checkExt(['jpg','png','gif']));exit();
            if ($pfwj and $pfwj->checkExt(['jpg','png','gif'])) {
                // 移动到框架应用根目录/public/upload/ 目录下
                $pfwjInfo = $pfwj->rule('uniqid')->move(ROOT_PATH . 'public' . DS . 'upload');
                //$json=json_encode($pfwjInfo,true);
                //dump($json);exit;
            //$array=toArray($pfwjInfo);
                if ($pfwj->getError())
                 {
                    $msg = $pfwj->getError();
                    $this->assign('errmsg', $msg);
                    return $this->fetch('keyan/result_error');
                }
            } else {
                $msg = "图片格式错误！";
                $this->assign('errmsg', $msg);
                return $this->fetch('keyan/result_error');
            }

            if ($rws and $rws->checkExt(['jpg','png','gif'])) {
                // 移动到框架应用根目录/public/upload/ 目录下
                $rwsInfo = $rws->rule('uniqid')->move(ROOT_PATH . 'public' . DS . 'upload');
                //dump($rws->getError());exit;
                
               if ($rws->getError()) {
                    $msg = $rws->getError();
                    $this->assign('errmsg', $msg);
                    return $this->fetch('keyan/result_error');
                }
            } else {
                $msg = "图片格式错误！";
                $this->assign('errmsg', $msg);
                return $this->fetch('keyan/result_error');
            }
          //dump(session('uid'));exit;
            //正确，写入数据库
            $form1_model = new Form1();

            $form1_model->pfwj = $pfwjInfo->getSavename();
            $form1_model->rws = $rwsInfo->getSavename();

            //$form1_model->uid = Session::get('uid');
            
            

            //$form1_model->isUpdate(true)->save(['id'=>$id,'xmmc'=>$xmmc,]);

            $form1_model->allowField(true)->save($_POST,['id'=>$id]);
            $msg='修改成功!';
            $this->redirect('keyan/result_success','msg='.$msg);
        }
    


}





}

