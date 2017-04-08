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
use app\home\model\Deal;
use app\home\model\Collection;

class Login extends Controller
{
	public function index()
	{
		return $this -> fetch('index');
	}

	//用户登录
	public function login()
	{
		$name = Session::get('name');
		$password = Session::get('password');

		$condition['name'] = $name;
		$condition['password'] = $password;
		$user = new User;
		$result = $user -> verifyUserInfo($condition);

		if ($result) {
			$session['id'] = $result['id'];
			$session['name'] = $result['name'];
			$session['image'] = $result['image'];
			session('userInfo', $result);
			//更新登录时间
			$update['id'] = $session['id'];
			$user -> updateLoginTime($update);
			$shop = new \app\home\controller\Shop();
			$commidity = $shop -> commidityInfoForIndex();
			//实例化搜索
			$search = new Search();
			$parent = $search -> getCommidityParentName(); 	//获取搜索框中的商品父类名称
			$this -> assign(
				[
					'name'      => $result['name'],
					'parent'    => $parent,
					'userImage' => $result['image'],
				]
			);
			return $this -> fetch('shop/shop');
		} else {
			return '<script>alert("登录失败，用户名或密码错误！");history.go(-1);</script>';
		}
	}

	//验证信息
	public function verifyInfo()
	{
		$name = input('post.name');
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

	//关于我们
	public function aboutUs()
	{
		//实例化搜索
		$search = new Search();
		$parent = $search -> getCommidityParentName();
		$name = Session::get('userInfo.name');
		$image = Session::get('userInfo.image');
		$this -> assign(
			[
				'name' => $name,
				'parent' => $parent,
				'userImage' => $image,
			]
		);

		return $this -> fetch('login/aboutUs');
	}

	//注销登录
	public function logout()
	{
		session::clear();
		$view = new View();
		return $view -> fetch('index');
	}
}