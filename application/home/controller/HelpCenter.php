<?php
namespace app\home\controller;
use think\Controller;
use think\Session;

class HelpCenter extends Controller
{
	//忘记密码并找回
	public function forgetPasswordLook()
	{
		$userId = Session::get('userInfo.id');
		$name = Session::get('userInfo.name');
		$userImage = Session::get('userInfo.image');
		$search = new Search();
		$parent = $search -> getCommidityParentName();
		$this -> assign(
			[
				'userId'    => $userId,
				'userImage' => $userImage,
				'name'      => $name,
				'parent'    => $parent,
			]
		);
		
		return $this -> fetch('forgetPassword');
	}

	//在线支付
	public function payOnline()
	{
		$userId = Session::get('userInfo.id');
		$name = Session::get('userInfo.name');
		$userImage = Session::get('userInfo.image');
		$search = new Search();
		$parent = $search -> getCommidityParentName();
		$this -> assign(
			[
				'userId'    => $userId,
				'userImage' => $userImage,
				'name'      => $name,
				'parent'    => $parent,
			]
		);
		
		return $this -> fetch('payOnline');
	}

	//银行汇款
	public function bankPay()
	{
		$userId = Session::get('userInfo.id');
		$name = Session::get('userInfo.name');
		$userImage = Session::get('userInfo.image');
		$search = new Search();
		$parent = $search -> getCommidityParentName();
		$this -> assign(
			[
				'userId'    => $userId,
				'userImage' => $userImage,
				'name'      => $name,
				'parent'    => $parent,
			]
		);
		
		return $this -> fetch('bankPay');
	}

	//常见问题
	public function FQA()
	{
		$userId = Session::get('userInfo.id');
		$name = Session::get('userInfo.name');
		$userImage = Session::get('userInfo.image');
		$search = new Search();
		$parent = $search -> getCommidityParentName();
		$this -> assign(
			[
				'userId'    => $userId,
				'userImage' => $userImage,
				'name'      => $name,
				'parent'    => $parent,
			]
		);
		
		return $this -> fetch('FQA');
	}
}