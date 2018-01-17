<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\User;

class UserInfo extends Controller
{
	//获取会员信息
	public function getUserInfo()
	{
		//获取管理员姓名，ＩＤ，头像
		$login = new Login();
		$userName = $login -> getUserSessionInfo()['name'];
		$userId = $login -> getUserSessionInfo()['id'];
		$adminImage = $login -> getUserSessionInfo()['image'];

		$status = [
			'0' => '否',
			'1' => '是'
		];

		$user = new User;
		$result = $user -> getUserInfo(4);

		if ($result) {
			$page = $result -> render();
			$this -> assign(
				[
					'name'       => $userName,
					'adminImage' => $adminImage,
					'userInfo'   => $result,
					'page'       => $page,
					'status'     => $status,
				]
			);
			return $this -> fetch("user_info/user");
		} else {
			return $this -> error("暂无数据):");
		}
	}

	//根据Id冻结，解冻会员信息
	public function freezeUserById($id)
	{
		$login = new Login();
		$userId = $login -> getUserSessionInfo()['id'];

		$isFreeze = input('post.freeze');
		
		if ($isFreeze == '否') {
			$isFreeze = 1;
		} else {
			$isFreeze = 0;
		}
		
		
    	$data = [
			'is_freeze'  => $isFreeze,
    	];

    	$user = new User;
		$res = $user -> updateUserById($id, $data);

		if ($res) {
			if ($isFreeze == '否') {
				return $this -> success('解冻会员信息成功！');
			} else {
				return $this -> success('冻结会员信息成功！');
			}
		} else {
			return $this -> error('冻结，解冻会员信息失败！');
		}		
	}


	//根据Ids批量冻结会员
	public function freezeUserByIds()
	{
		//获取选择的会员
		$userIds = json_decode(input('post.userIds'), true);
		if (empty($userIds)) {
			return $this -> error("请选择要冻结的会员！！！");
		} else {
			$data = [
				'is_freeze'  => 1,
	    	];
			$user = new User;
			$result = $user -> freezeUserByIds($userIds, $data);

			if ($result) {
				$userIds = implode(',', $userIds);
				return $this -> success('冻结 ' . $userIds . ' 成功！');
			} else {
				return $this -> error("冻结失败！请选择未冻结的用户！" . $user -> getLastSql());
			}
		}
	}

	//根据Ids批量解冻会员
	public function unFreezeUserByIds()
	{
		//获取选择的会员
		$userIds = json_decode(input('post.userIds'), true);
		if (empty($userIds)) {
			return $this -> error("请选择要解冻的会员！！！");
		} else {
			$data = [
				'is_freeze'  => 0,
	    	];
			$user = new User;
			$result = $user -> freezeUserByIds($userIds, $data);

			if ($result) {
				$userIds = implode(',', $userIds);
				return $this -> success('解冻 ' . $userIds . ' 成功！');
			} else {
				return $this -> error("解冻失败！请选择未解冻的用户！" . $user -> getLastSql());
			}
		}
	}

	//根据搜索条件查询会员
	public function searchUser()
	{
		//查询条件
		$condition = input('post.condition');
		if (empty($condition)) {
			return $this -> error("请输入要查询的条件！！");
		} else {
			$user = new User;
			$result = $user -> searchUser($condition);

			//是否冻结和是否学生的标志
			$status = [
				'0' => '否',
				'1' => '是'
			];
			$data['data'] = $result;
			$data['status'] = $status;

			if ($result) {
				return $data;
			} else {
				return $this -> error("暂无数据):");
			}
		}
	}
}