<?php
namespace app\admin\controller;
use think\Controller;
use think\Validate;
use app\admin\model\AdminUser;

class AdminUserInfo extends Controller
{
	//获取管理员信息
	public function getAdminUserInfo()
	{
		$login = new Login();
		$userName = $login -> getUserSessionInfo()['name'];
		$userId = $login -> getUserSessionInfo()['id'];
		$adminImage = $login -> getUserSessionInfo()['image'];

		$adminUser = new AdminUser;
		$result = $adminUser -> getAdminUserInfo(7);

		if ($result) {
			$page = $result -> render();
			$this -> assign(
				[
					'name'          => $userName,
					'adminImage'    => $adminImage,
					'adminUserInfo' => $result,
					'page'          => $page,
				]
			);
			return $this -> fetch("adminUser");
		} else {
			return $this -> error("暂无数据):");
		}
	}

	//根据ID删除管理员
	public function deleteAdminUserById($id)
	{
		$adminUser = new AdminUser;
		$res = $adminUser -> deleteAdminUserById($id);

		if ($res) {
			$this -> success("删除管理员成功！");
		} else {
			$this -> error("删除管理员失败！");
		}
	}

	//根据Id修改管理员信息
	public function modifyAdminUserById($id)
	{
		$login = new Login();
		$userId = $login -> getUserSessionInfo()['id'];

		$adminUserName = input('post.adminUserName');
		$adminUserEmail = input('post.adminUserEmail');
		
    	$data = [
			'name'  => $adminUserName,
			'email' => $adminUserEmail,
    	];

    	$adminUser = new AdminUser;
		$res = $adminUser -> updateAdminUserById($id, $data);

		if ($res) {
			return $this -> success('修改管理员信息成功！');
		} else {
			return $this -> error('修改管理员信息失败！');
		}		
	}

	//添加管理员
	public function addAdminUser()
	{
		$login = new Login();
		$userId = $login -> getUserSessionInfo()['id'];

		$adminUserName = input('post.adminUserName');
		$adminUserEmail = input('post.adminUserEmail');
		// if (empty($adminUserName)) {
		// 	return $this -> error('管理员姓名不允许为空！');
		// }
		// if (empty($adminUserEmail)) {
		// 	return $this -> error('管理员邮箱不允许为空！');
		// }
		// $pattern = '/^([0-9A-Za-z\-_\.]+)@([0-9a-z]+\.[a-z]{2,3}(\.[a-z]{2})?)$/i';
		// if (! preg_match($pattern, $adminUserEmail)) {
		// 	return $this -> error("邮箱格式不合法！！！");
		// }
		$data = [
			'name'          => $adminUserName,
			'email'         => $adminUserEmail,
			'register_time' => time(),
		];
		$rules = [
			'name'  => 'require|max:50',
			'email' => 'require|email',
		];
		$message = [
			'name.require'  => '管理员姓名不允许为空！',
			'name.max'      => '管理员姓名长度不允许超过50个字符！',
			'email.require' => '管理员邮箱不允许为空！',
			'email.email'   => '邮箱格式不合法！！！',
		];
		$validate = $this -> validate($data, $rules, $message);

		if ($validate !== true) {
			return $this -> error($validate);
		} else {
			$adminUser = new AdminUser;
			$res = $adminUser -> addAdminUser($data);
			if ($res) {
				return $this -> success('添加管理员成功！');
			} else {
				return $this -> error('添加管理员失败！');
			}
		}
	}

	//根据Ids批量删除管理员
	public function deleteAdminUserByIds()
	{
		//获取选择的管理员
		$adminUserIds = json_decode(input('post.adminUserIds'), true);
		if (empty($adminUserIds)) {
			return $this -> error("请选择要删除的管理员！！！");
		} else {
			$adminUser = new AdminUser;
			$result = $adminUser -> deleteAdminUserByIds($adminUserIds);

			if ($result) {
				$adminUserIds = implode(',', $adminUserIds);
				return $this -> success('删除 ' . $adminUserIds . ' 成功！');
			} else {
				return $this -> error("删除失败！" . $adminUser -> getLastSql());
			}
		}
	}

	//根据搜索条件查询管理员
	public function searchAdminUser()
	{
		//查询条件
		$condition = input('post.condition');
		if (empty($condition)) {
			return $this -> error("请输入要查询的条件！！");
		} else {
			$adminUser = new AdminUser;
			$result = $adminUser -> searchAdminUser($condition);

			if ($result) {
				return $result;
			} else {
				return $this -> error("暂无数据):");
			}
		}
	}
}