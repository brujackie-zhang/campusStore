<?php
namespace app\home\model;
use think\Model;
use think\Db;

class User extends Model
{
	const TB_USER = 'user';

	//验证登录信息
	public function verifyUserInfo($codition)
	{
		return Db::table(self::TB_USER) -> where($codition) -> find();
	}

	//更新最后登录时间
	public function updateLoginTime($userId)
	{
		return Db::table(self::TB_USER) -> where($userId) -> setField('login_time', time());
	}

	//根据用户ID 获取用户信息
	public function getUserInfoById($id)
	{
		$fields = [
			self::TB_USER . '.id as id',
			self::TB_USER . '.name as name',
			self::TB_USER . '.sex as sex',
			self::TB_USER . '.image as image',
			self::TB_USER . '.school as school',
		];

		$result = Db::table(self::TB_USER)
			-> field($fields)
			-> where(self::TB_USER . '.id', $id)
			-> find();

		return $result = $result ? $result : "";
	}

	//根据用户Id 修改用户信息
	public function updateUserInfoById($id, $data)
	{
		return Db::table(self::TB_USER) -> where(self::TB_USER . '.id', $id) -> update($data);
	}

	//注册用户
	public function registerUser($data)
	{
		return Db::table(self::TB_USER) -> insert($data);
	}

	//根据用户名查询数据库中用户
	public function getUsersByUserName($userName)
	{
		return Db::table(self::TB_USER) -> where(self::TB_USER . '.name', $userName) -> select();
	}

	//根据用户名重置密码
	public function resetPassword($userName, $data)
	{
		return DB::table(self::TB_USER) -> where(self::TB_USER . '.name', $userName) -> update($data);
	}
}