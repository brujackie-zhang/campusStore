<?php
namespace app\home\controller;
use think\Controller;
use think\Session;
use think\Db;
use think\Request;
use think\Loader;
use think\Validate;
use app\home\model\User;

class UserInfo extends Controller
{
	//修改用户密码
	public function editPassword()
	{
		if (Request::instance() -> isPost()) {
			$password = input('post.password');
			$newPassword = input('post.newPassword');
			$verifyPassword = input('post.verifyPassword');
			$id = Session::get('userInfo.id');
			$userImage = Session::get('userInfo.image');
			$name = Session::get('userInfo.name');

			$search = new Search();
			$parent = $search -> getCommidityParentName();
			$this -> assign(
				[
					'name'      => $name,
					'parent'    => $parent,
					'userImage' => $userImage,
				]
			);

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
			$name = Session::get('userInfo.name');
			$userImage = Session::get('userInfo.image');
			$search = new Search();
			$parent = $search -> getCommidityParentName();
			$this -> assign(
				[
					'name'      => $name,
					'parent'    => $parent,
					'userImage' => $userImage,
				]
			);

			return $this -> fetch('editPassword');
		}
	}

	//用户头像上传
	public function userPhotoUpload()
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
		
		return $this -> fetch('userImage');
	}

	//展示用户信息
	public function getUserInfo()
	{
		$id = Session::get('userInfo.id');
		$image = Session::get('userInfo.image');
		$this -> assign('userImage', $image);
		//实例化搜索
		$search = new Search();
		$parent = $search -> getCommidityParentName();
		$this -> assign('parent', $parent);

		$res = Db::table('user') -> where('id', $id) -> find();

		if ($res) {
			$this -> assign('name', $res['name']);
			$this -> assign('userInfo', $res);
			return $this -> fetch('userInfo');
		} else {
			return $this -> error('暂无数据o(╯□╰)o');
		}
	}

	//更新用户信息
	public function updateUserInfoById($id)
	{
		$image = input('post.image');
		
    	$data = [
			'image' => $image,
    	];

    	$user = new User;
		$res = $user -> updateUserInfoById($id, $data);

		//实例化搜索
		$search = new Search();
		$parent = $search -> getCommidityParentName();

		if ($res) {
			$this -> success('上传头像成功！下次登录生效！');
		} else {
			return $this -> error('上传头像失败！'.$res);
		}
	}

	//登录界面重置用户密码
	public function resetPassword()
	{
		$password = md5(input('post.forgetPassword'));
		// $userName = Session::get('userName');
		$userName = input('post.userName') ? input('post.userName') : Session::get('userInfo.name');

		$data = [
			'password' => $password,
		];
		$user = new User;
		$result = $user -> resetPassword($userName, $data);

		if ($result) {
			return $this -> success("重置密码成功！");
		} else {
			return $this -> error('重置密码失败，两次重置密码相同，请重新设置新密码！');
		}
	}
}