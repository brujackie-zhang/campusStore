<?php
namespace app\home\controller;
use think\View;
use think\Controller;
use think\Validate;
use think\Session;
use think\Db;
use think\Request;
use think\Loader;
use app\home\model\User;

class Register extends Controller
{
	public function index()
	{
		$view = new View();
		return $view -> fetch('index');
	}

	//用户注册
	public function register()
	{
		$province       = input('post.province');
		$city           = input('post.city');
		$district       = input('post.district');
		$address        = input('post.address');
		$name           = input('post.name');
		$password       = input('post.password');
		$verifyPassword = input('post.verifyPassword');
		$sex            = input('post.sex');
		$is_student     = input('post.isStudent');
		$school         = input('post.school');
		$captcha        = input('post.captcha');
		$register_time  = time();

		//判断用户名是否存在
		$user = new User();
		$userNames = $user -> getUsersByUserName($name);
		if ($userNames) {
			return $this -> error("此用户名已被占用，请重新输入！");
		}
		if ($password != $verifyPassword) {
			return $this -> error('两次输入的密码不一致，请重新输入！');
		}
		if ($district == '') {
			$district = '';
		}
		$validate = Loader::validate('User');
		$data = [
			'province'       => $province,
			'city'           => $city,
			'address'        => $address,
			'name'           => $name,
			'password'       => $password,
			'verifyPassword' => $verifyPassword,
			'sex'            => $sex,
			'is_student'     => $is_student,
			'school'         => $school,
			'captcha'        => $captcha,
		];

		if (! $validate -> scene('register') -> check($data)) {
			return $this -> error($validate -> getError());
		} else {
			$address = $province . $city . $district . $address;
			$value = [
				'name'          => $name,
				'password'      => md5($password),
				'sex'           => $sex,
				'address'       => $address,
				'register_time' => $register_time,
				'is_student'    => $is_student,
				'school'        => $school,
			];
			
			$res = $user -> registerUser($value);

			if ($res) {
				return $this -> success('用户注册成功！');
			} else {
				return $this -> error('用户注册失败失败！');
			}
		}	
	}
}