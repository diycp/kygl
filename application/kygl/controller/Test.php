<?php 
namespace app\kygl\Controller;
use think\Controller;
use think\Db;
class Test extends Controller{



public function test(){




$test=Db::query("select a.*,b.bh from student a,xy b");

dump($test);exit;




}







}








 ?>