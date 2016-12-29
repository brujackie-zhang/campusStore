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

class Login extends Controller
{
	public function index()
	{
		// $view = new View();
		// return $view -> fetch('index');
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
		// echo $user -> getLastSql();

		if ($result) {
			$session['id'] = $result['id'];
			$session['name'] = $result['name'];
			session('userInfo', $session);
			//更新登录时间
			$update['id'] = $session['id'];
			$user -> updateLoginTime($update);
			$shop = new \app\home\controller\Shop();
			$commidity = $shop -> getCommidities();

			$view = new View();
			$view -> assign('name', $result['nickname']);
			$view -> assign('list', $commidity);
			return $view -> fetch('shop/shop');
		} else {
			return $this -> error('Login failure,user name or password error!');

		}
	}

	//验证信息
	public function verifyInfo()
	{
		$name = input('post.name');
		$password = input('post.password');
		$captcha = input('post.captcha');
		$data = [
			'name' => $name,
			'password' => $password,
			'captcha' => $captcha,
		];
		$rules = [
			'name' => 'require|max:50',
			'password' => 'require|min:6',
			'captcha' => 'require|captcha',
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

	//service
	public function service()
	{
		$view = new View();
		return $view -> fetch('shop/service');
	}

	//返回首页
	public function homePage()
	{
		$view = new View();
		return $view -> fetch('homePage');
	}

	//帮助信息
	public function help()
	{
		$view = new View();
		return $view -> fetch('help');
	}

	//注销登录
	public function logout()
	{
		session::clear();
		$view = new View();
		return $view -> fetch('index');
	}

	//修改用户密码
	public function editPassword()
	{
		// return $this -> fetch ('editPassword');
		if (Request::instance() -> isPost()) {
			$password = input('post.password');
			$newPassword = input('post.newPassword');
			$verifyPassword = input('post.verifyPassword');
			$id = Session::get('userInfo.id');

			$userInfo = Db::table('user') -> where('id', $id) -> find();

			if (md5($password) !== $userInfo['password']) {
				return $this -> error('旧密码输入不正确，请重新输入！');
			}
			if (md5($newPassword) !== md5($verifyPassword)) {
				return $this -> error('两次输入的密码不一致，请重新输入！');
			}

			$validate = Loader::validate('User');
			$data = [
				'password' => $password,
				'newPassword' => $newPassword,
			];
			if (! $validate -> scene('password') -> check($data)) {
				return $this -> error($validate -> getError());
			} else {
				$newPassword = md5($newPassword);
				$res = Db::table('user') -> where('id', $id) -> update(['password' => $newPassword]);

				if ($res) {
					return $this -> success('修改密码成功！');
				} else {
					return $this -> error('修改密码失败！');
				}
			}
		} else {
			return $this -> fetch('editPassword');
		}
	}

	//展示用户信息
	public function getUserInfo()
	{
		$id = Session::get('userInfo.id');

		$res = Db::table('user') -> where('id', $id) -> find();

		if ($res) {
			$this -> assign('userInfo', $res);
			return $this -> fetch('userInfo');
		} else {
			return $this -> error('暂无数据o(╯□╰)o');
		}
	}

	//添加用户地址
	public function addAddress()
	{
		if ($this -> getAddress()) {
			if (Request::instance() -> isPost()) {
				$province = input('post.province');
				$city     = input('post.city');
				$district = input('post.district');
				$address  = input('post.address');
				$receiver = input('post.receiver');
				$mobile   = input('post.mobile');
				$id       = Session::get('userInfo.id');

				$validate = Loader::validate('Address');
				$data = [
					'province' => $province,
					'city'     => $city,
					'address'  => $address,
					'receiver' => $receiver,
					'mobile'   => $mobile,
				];

				// if (! $validate -> scene('add') -> check($data)) {
				if (! $validate -> check($data)) {
					return $this -> error($validate -> getError());
				} else {
					$address = $province . $city . $district . $address;
					$value = [
						'user_id'      => $id,
						'address_info' => $address,
						'receiver'     => $receiver,
						'mobile'       => $mobile,
					];
					$res = Db::table('address') -> insert($value);
					if ($res) {
						return $this -> success('地址添加成功！');
					} else {
						return $this -> error('地址添加失败！');
					}
				}
			} else {
				return $this -> fetch('address');
			}
		}
	}

	//获得用户已有地址
	public function getAddress()
	{
		$id = Session::get('userInfo.id');

		$result = Db::table('address')
			-> join('user', 'user.id = address.user_id')
			-> where('user_id', $id)
			-> select();

		if ($result) {
			$this -> assign('addressInfo', $result);
			return $this -> fetch('address');
		} else {
			$this -> assign('addressInfo', '');
			return $this -> fetch('address');
		}
	}
}