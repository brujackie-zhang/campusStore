<?php
namespace app\home\controller;
use think\View;
use app\home\model\Index as M;
class Index
{
	public function index()
	{
		$view = new View();
		return $view -> fetch('index');
	}

	//获取验证码
	public function getVerify()
	{
		$license = input('license');
    	$host = request()->root(true);
    	$key = base64_encode('host='.request()->root(true).'&license='.$license);
    	$content = file_get_contents('http://www.shangtaosoft.com/index.php?m=Api&c=License&a=verifyWSTMartLicense&key='.$key);
    	$json = json_decode($content,true);
    	$rs = array('status' => 1);
    	if($json['status'] == 1){
    		$m = new M();
    		$rs = $m->saveLicense();
    	}
    	$rs['license'] = $json;
		return $rs;
	}
}