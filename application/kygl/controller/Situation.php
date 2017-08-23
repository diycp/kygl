<?php  
namespace app\kygl\Controller;
use think\Controller;
use think\Model;
use think\Db;
class Situation extends Controller{


public function situation(){
//$res=Db::query("select a.username,b.xmmc from sys_user a,sys_form_1 b,sys_form_2 c where a.id=b.uid and a.id=c.uid");
//dump($res);exit;
//?  why repeat 4?   damn it !!
$xiangmu_model = model('xiangmu');
$xiangmus=$xiangmu_model->all();
//dump($xiangmus);exit;
$user_model = model('user');
$users=$user_model->all(['usergroup'=>'2']);
//dump($users);exit;

//$this->assign('year',date('Y'));
 $this->assign('xiangmus',$xiangmus);
 $this->assign('users',$users);
 return $this->fetch('situation/chaxun');

}

public function chaxunjieguo(){
//dump($_POST);exit;
       $xmid = $_POST['xmid'];
       //dump($xmid);exit;
        //$year = input('post.year');
        //$uid = intval(input('post.uid'));
          $year=$_POST['year'];
          $uid=$_POST['uid'];
          //dump($year);exit;
        $xiangmu_model = model('xiangmu');
        $xiangmu = $xiangmu_model->get(['id'=>$xmid]);
        $this->assign('xiangmu',$xiangmu);

        $form = model('form'.$xmid);
        if($uid == "" and $year == ""){
            /*$data = $form->all(function($query){
                $query->order('create_time','asc');
            });*/
            $data=Db::query("select a.username,b.* from sys_user a,sys_form_$xmid b where a.id=b.uid order by update_time desc");
//$data=Db::query("select a.*,b.* from sys_user a,sys_form_$xmid b where status=1 order by update_time desc");
     //dump($data);exit;
 }elseif($uid == "" and $year != ""){
            //$data = $form->all(['year'=>$year]);
            $data=Db::query("select a.username,b.* from sys_user a,sys_form_$xmid b where a.id=b.uid and b.year=$year order by update_time desc");
           



    //$data=Db::query("select * from sys_form_1 a,select * from sys_form_2 b where a.status=1");

             //dump($data);exit;






        }elseif($uid != "" and $year == ""){
            //$data = $form->all(['uid'=>$uid]);

    $data=Db::query("select a.username,b.* from sys_user a,sys_form_$xmid b where a.id=b.uid and b.uid=$uid order by update_time desc");



        }else{
            //$data = $form->all(['uid'=>$uid,'year'=>$year]);



     $data=Db::query("select a.username,b.* from sys_user a,sys_form_$xmid b where a.id=b.uid and b.uid=$uid and b.year=$year order by update_time desc");

}
         //dump($data);exit;
        $this->assign('data',$data);
        $this->assign('flag','chaxun');
        return $this->fetch('keyan/jindu/table'.$xmid);


}

public function tongji(){
        
        $xiangmu_model = model('xiangmu');
        $xiangmus=$xiangmu_model->all();
       //dump($xiangmus['id']);exit;
      
        $form1 = new \app\kygl\model\Form1;
        $f1_1 = count($form1->all(['status'=>'1']));
        $f2_1 = count($form1->all(['status'=>'2']));
        $f3_1 = count($form1->all(['status'=>'3']));

        $form2 = new \app\kygl\model\Form2;
        $f1_2 = count($form2->all(['status'=>'1']));
        $f2_2 = count($form2->all(['status'=>'2']));
        $f3_2 = count($form2->all(['status'=>'3']));

foreach ($xiangmus as $k=>$v){
            //$data[$k]['id'] = $v['id'];
            //$data[$k]['title'] = $v['title'];
            //$data[$k]['id'];
            //$data[$k]['title'];
            //$xiangmus[$k]['id']=$v['id'];
            //$xiangmus[$k]['title']=$v['title'];
            $temp1 = 'f1_'.($k+1);
            $temp2 = 'f2_'.($k+1);
            $temp3 = 'f3_'.($k+1);

                 //echo $data[$k]['id']; 
                //exit;

                  //echo $temp1;

            $xiangmus[$k]['f1'] = $$temp1;
           $xiangmus[$k]['f2'] = $$temp2;
            $xiangmus[$k]['f3'] = $$temp3;

}

       $this->assign('xiangmus',$xiangmus);
        return $this->fetch('situation/tongji');



}

}
























?>