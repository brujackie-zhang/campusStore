<?php
namespace app\admin\controller;
use think\View;
use think\Controller;
use think\Validate;
use think\Session;
use think\Db;
use think\Request;
use think\Loader;
use app\admin\model\AdminUser;

class Login extends Controller
{
	public function index()
	{
		return $this -> fetch('index');
	}

	public function index1()
	{
		return $this -> fetch('index1');
	}

	//用户登录
	public function login()
	{
		$name = Session::get('name');
		$password = Session::get('password');

		$condition['name'] = $name;
		$condition['password'] = $password;
		$user = new AdminUser;
		$result = $user -> verifyUserInfo($condition);

		if ($result) {
			$session['id'] = $result['id'];
			$session['name'] = $result['name'];
			$session['image'] = $result['image'];
			session('userInfo', $result);
			//更新登录时间
			$update['id'] = $session['id'];
			$user -> updateLoginTime($update);

			$commidityParent = new CommidityInfo();
			$commidityParentInfo = $commidityParent -> getCommidityParentInfoForLogin();
			$this -> assign(
				[
					'name'                => $result['name'],
					'adminImage'          => $result['image'],
					'commidityParentInfo' => $commidityParentInfo,
					'page'                => $commidityParentInfo -> render(),
				]
			);
			return $this -> fetch('commidity_info/commidityParent');
		} else {
			return '<script>alert("登录失败，用户名或密码错误！");location.href="http://campusstore/admin/login";</script>';
		}
	}

	//验证信息
	public function verifyInfo()
	{
		$name = input('post.userName');
		$password = input('post.password');
		$captcha = input('post.captcha');
		$data = [
			'name'     => $name,
			'password' => $password,
			'captcha'  => $captcha,
		];
		$rules = [
			'name'     => 'require|max:50',
			'password' => 'require|min:6',
			'captcha'  => 'require|captcha',
		];
		$validate = $this -> validate($data, $rules);

		if ($validate !== true) {
			return $validate;
		} else {
			session('name', $name);
			session('password', md5($password));

			return 'success';
		}
	}

	//获取用户信息
	public function getUserSessionInfo()
	{
		return Session::get('userInfo');
	}

	//注销登录
	public function logout()
	{
		session::clear();
		$view = new View();
		return $view -> fetch('index');
	}
}