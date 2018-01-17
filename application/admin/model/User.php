<?php
namespace app\admin\model;
use think\Model;
use think\Db;

class User extends Model
{
	const TB_USER = 'user';

	//获取会员信息
	public function getUserInfo($perPage = 10)
	{
		$fields = [
			self::TB_USER . '.id as id',
			self::TB_USER . '.name as name',
			self::TB_USER . '.nickname as nickname',
			self::TB_USER . '.sex as sex',
			self::TB_USER . '.mobile_phone as mobilePhone',
			self::TB_USER . '.address as address',
			self::TB_USER . '.is_freeze as isFreeze',
			'FROM_UNIXTIME(' . self::TB_USER . '.register_time, "%Y-%m-%d %H:%i:%s") as registerTime',
			'FROM_UNIXTIME(' . self::TB_USER . '.login_time, "%Y-%m-%d %H:%i:%s") as loginTime',
			self::TB_USER . '.is_student as isStudent',
			self::TB_USER . '.school as school',
		];
		$total = $this -> getUserInfoTotal();

		$result = Db::table(self::TB_USER)
			-> field($fields)
			-> order(self::TB_USER . '.id', 'asc')
			-> paginate($perPage, $total);

		return $result = $result ? $result : "";
	}

	//获取会员信息总数
	public function getUserInfoTotal()
	{
		$fields = [
			self::TB_USER . '.id as id',
			self::TB_USER . '.name as name',
			self::TB_USER . '.nickname as nickname',
			self::TB_USER . '.sex as sex',
			self::TB_USER . '.mobile_phone as mobilePhone',
			self::TB_USER . '.address as address',
			self::TB_USER . '.is_freeze as isFreeze',
			'FROM_UNIXTIME(' . self::TB_USER . '.register_time, "%Y-%m-%d %H:%i:%s") as registerTime',
			'FROM_UNIXTIME(' . self::TB_USER . '.login_time, "%Y-%m-%d %H:%i:%s") as loginTime',
			self::TB_USER . '.is_student as isStudent',
			self::TB_USER . '.school as school',
		];

		$result = Db::table(self::TB_USER)
			-> field($fields)
			-> order(self::TB_USER . '.id', 'asc')
			-> count();

		return $result = $result ? $result : "";
	}

	//根据ID更新会员信息
	public function updateUserById($id, $data)
	{
		return Db::table(self::TB_USER) -> where(self::TB_USER . '.id', $id) -> update($data);
	}

	//根据Ids批量冻结会员
	public function freezeUserByIds($ids, $data)
	{
		return Db::table(self::TB_USER) -> where(self::TB_USER . '.id', 'in', $ids) -> update($data);
	}

	//根据查询条件查询会员
	public function searchUser($condition = '')
	{
		$fields = [
			self::TB_USER . '.id as id',
			self::TB_USER . '.name as name',
			self::TB_USER . '.nickname as nickname',
			self::TB_USER . '.sex as sex',
			self::TB_USER . '.mobile_phone as mobilePhone',
			self::TB_USER . '.address as address',
			self::TB_USER . '.is_freeze as isFreeze',
			'FROM_UNIXTIME(' . self::TB_USER . '.register_time, "%Y-%m-%d %H:%i:%s") as registerTime',
			'FROM_UNIXTIME(' . self::TB_USER . '.login_time, "%Y-%m-%d %H:%i:%s") as loginTime',
			self::TB_USER . '.is_student as isStudent',
			self::TB_USER . '.school as school',
		];

		if (isset($condition) && !empty($condition)) {
			Db::table(self::TB_USER) -> where(self::TB_USER . '.id', 'like', '%' . $condition . '%')
				-> whereOr(self::TB_USER . '.name', 'like', '%' . $condition . '%')
				-> whereOr(self::TB_USER . '.sex', 'like', '%' . $condition . '%')
				-> whereOr(self::TB_USER . '.address', 'like', '%' . $condition . '%');
		}

		$result = Db::table(self::TB_USER)
			-> field($fields)
			-> order(self::TB_USER . '.id', 'asc')
			-> select();

		return $result = $result ? $result : "";
	}
}