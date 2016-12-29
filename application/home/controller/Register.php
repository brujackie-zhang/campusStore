<?php
namespace app\home\controller;
use think\View;
use think\Controller;
use think\Validate;
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

	//验证注册信息
	public function verifyInfo()
	{
		//TODO:
	}

	//用户注册
	public function register()
	{
		// $user = new User;
		// $user -> name = input('post.name');
		// $user -> nickname = input('post.nickname');
		// $user -> password = input('post.password');
		// $verifyName = input('post.verifyName');
		// $user -> question = input('post.question');
		// $user -> answer = input('post.answer');
		// $user -> sex = input('post.sex');
		// $user -> address = input('post.province').input('post.city').input('post.district').input('post.address');
		// $user -> is_student = input('post.is_student');
		// $user -> school = input('post.input-school');
		// $captcha = input('post.captcha');
		// $user -> register_time = time();

		// if ($user -> password != $verifyName) {
		// 	return '两次输入的密码不一致，请重新输入！';
		// }
		// $result = $this -> validate(
		// 	[
		// 		'name' => $user -> name,
		// 		'nickname' => $user -> nickname,
		// 		'password' => $user -> password,
		// 		'question' => $user -> question,
		// 		'answer' => $user -> answer,
		// 		'sex' => $user -> sex,
		// 		'address' => $user -> address,
		// 		'is_student' => $user -> is_student,
		// 		'school' => $user -> school,
		// 		'captcha' => $captcha,
		// 	],
		// 	[
		// 		'name' => 'require|max:50',
		// 		'nickname' => 'require|max:50',
		// 		'password' => 'require|min:6',
		// 		'question' => 'require|max:80',
		// 		'answer' => 'require|max:80',
		// 		'sex' => 'require',
		// 		'address' => 'require',
		// 		'is_student' => 'number|between:0,1',
		// 		'school' => 'require',
		// 		'captcha' => 'require|captcha',
		// 	]
		// );

		// if ($result !== true) {
		// 	return $result;
		// }

		// $user -> password = md5($user -> password);

		// if ($user -> save()) {
		// 	return $this -> success('注册成功！');
		// } else {
		// 	return $this -> success('注册失败！');
		// }

		//法二
		$province       = input('post.province');
		$city           = input('post.city');
		$district       = input('post.district');
		$address        = input('post.address');
		$name           = input('post.name');
		$nickname       = input('post.nickname');
		$password       = input('post.password');
		$verifyPassword = input('post.verifyPassword');
		$question       = input('post.question');
		$answer         = input('post.answer');
		$sex            = input('post.sex');
		$is_student     = input('post.is_student');
		$school         = input('post.input_school');
		$captcha        = input('post.captcha');
		$register_time  = time();

		if ($password != $verifyPassword) {
			return '两次输入的密码不一致，请重新输入！';
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
			'nickname'       => $nickname,
			'password'       => $password,
			'verifyPassword' => $verifyPassword,
			'question'       => $question,
			'answer'         => $answer,
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
				'nickname'      => $nickname,
				'password'      => md5($password),
				'question'      => $question,
				'answer'        => $answer,
				'sex'           => $sex,
				'address'       => $address,
				'register_time' => $register_time,
				'is_student'    => $is_student,
				'school'        => $school,
			];
			$res = Db::table('user') -> insert($value);
			if ($res) {
				return $this -> success('用户注册成功！');
			} else {
				return $this -> error('用户注册失败失败！');
			}
		}

		//ajax方法有问题
		
		// if (Request::instance() -> isPost()) {
		// 	$province       = input('post.province');
		// 	$city           = input('post.city');
		// 	$district       = input('post.district');
		// 	$address        = input('post.address');
		// 	$name           = input('post.name');
		// 	$nickname       = input('post.nickname');
		// 	$password       = input('post.password');
		// 	$verifyPassword = input('post.verifyPassword');
		// 	$question       = input('post.question');
		// 	$answer         = input('post.answer');
		// 	$sex            = input('post.sex');
		// 	$is_student     = input('post.isStudent');
		// 	$school         = input('post.school');
		// 	$captcha        = input('post.captcha');
		// 	$register_time  = time();

		// 	if ($password != $verifyPassword) {
		// 		return '两次输入的密码不一致，请重新输入！';
		// 	}
		// 	if ($district == '') {
		// 		$district = '';
		// 	}
		// 	$validate = Loader::validate('User');
		// 	$data = [
		// 		'province'       => $province,
		// 		'city'           => $city,
		// 		'address'        => $address,
		// 		'name'           => $name,
		// 		'nickname'       => $nickname,
		// 		'password'       => $password,
		// 		'verifyPassword' => $verifyPassword,
		// 		'question'       => $question,
		// 		'answer'         => $answer,
		// 		'sex'            => $sex,
		// 		'is_student'     => $is_student,
		// 		'school'         => $school,
		// 		'captcha'        => $captcha,
		// 	];

		// 	if (! $validate -> scene('register') -> check($data)) {
		// 		return $this -> error($validate -> getError());
		// 	} else {
		// 		$address = $province . $city . $district . $address;
		// 		$value = [
		// 			'name'          => $name,
		// 			'nickname'      => $nickname,
		// 			'password'      => md5($password),
		// 			'question'      => $question,
		// 			'answer'        => $answer,
		// 			'sex'           => $sex,
		// 			'address'       => $address,
		// 			'register_time' => $register_time,
		// 			'is_student'    => $isStudent,
		// 			'school'        => $school,
		// 		];
		// 		$res = Db::table('user') -> insert($value);
		// 		if ($res) {
		// 			return $this -> success('地址添加成功！');
		// 		} else {
		// 			return $this -> error('地址添加失败！');
		// 		}
		// 	}
		// } else {
		// 	return $this -> fetch('index');
		// }

	}
}