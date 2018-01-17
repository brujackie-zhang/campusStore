<?php
namespace app\admin\model;
use think\Model;
use think\Db;

class AdminUser extends Model
{
	const TB_ADMIN_USER = 'admin_user';

	//验证登录信息
	public function verifyUserInfo($codition)
	{
		return Db::table(self::TB_ADMIN_USER) -> where($codition) -> find();
	}

	//更新最后登录时间
	public function updateLoginTime($userId)
	{
		return Db::table(self::TB_ADMIN_USER) -> where($userId) -> setField('login_time', time());
	}

	//根据用户Id 修改用户信息
	public function updateUserInfoById($id, $data)
	{
		return Db::table(self::TB_ADMIN_USER) -> where(self::TB_ADMIN_USER . '.id', $id) -> update($data);
	}

	//注册用户
	public function registerUser($data)
	{
		return Db::table(self::TB_ADMIN_USER) -> insert($data);
	}

	//根据用户名查询数据库中用户
	public function getUsersByUserName($userName)
	{
		return Db::table(self::TB_ADMIN_USER) -> where(self::TB_ADMIN_USER . '.name', $userName) -> select();
	}

	//根据用户名重置密码
	public function resetPassword($userName, $data)
	{
		return DB::table(self::TB_ADMIN_USER) -> where(self::TB_ADMIN_USER . '.name', $userName) -> update($data);
	}

	//获取管理员信息
	public function getAdminUserInfo($perPage = 10)
	{
		$fields = [
			self::TB_ADMIN_USER . '.id as id',
			self::TB_ADMIN_USER . '.name as name',
			self::TB_ADMIN_USER . '.password as password',
			self::TB_ADMIN_USER . '.email as email',
			'FROM_UNIXTIME(' . self::TB_ADMIN_USER . '.register_time, "%Y-%m-%d %H:%i:%s") as registerTime',
			'FROM_UNIXTIME(' . self::TB_ADMIN_USER . '.login_time, "%Y-%m-%d %H:%i:%s") as loginTime',
		];
		$total = $this -> getAdminUserInfoTotal();

		$result = Db::table(self::TB_ADMIN_USER)
			-> field($fields)
			-> order(self::TB_ADMIN_USER . '.id', 'asc')
			-> paginate($perPage, $total);

		return $result = $result ? $result : "";
	}

	//获取管理员信息总数
	public function getAdminUserInfoTotal()
	{
		$fields = [
			self::TB_ADMIN_USER . '.id as id',
			self::TB_ADMIN_USER . '.name as name',
			self::TB_ADMIN_USER . '.password as password',
			self::TB_ADMIN_USER . '.email as email',
			'FROM_UNIXTIME(' . self::TB_ADMIN_USER . '.register_time, "%Y-%m-%d %H:%i:%s") as registerTime',
			'FROM_UNIXTIME(' . self::TB_ADMIN_USER . '.login_time, "%Y-%m-%d %H:%i:%s") as loginTime',
		];

		$result = Db::table(self::TB_ADMIN_USER)
			-> field($fields)
			-> order(self::TB_ADMIN_USER . '.id', 'asc')
			-> count();

		return $result = $result ? $result : "";
	}

	//根据ID删除管理员信息
	public function deleteAdminUserById($id)
	{
		return Db::table(self::TB_ADMIN_USER) -> where(self::TB_ADMIN_USER . '.id', $id) -> delete();
	}

	//根据ID更新管理员信息
	public function updateAdminUserById($id, $data)
	{
		return Db::table(self::TB_ADMIN_USER) -> where(self::TB_ADMIN_USER . '.id', $id) -> update($data);
	}

	//添加管理员
	public function addAdminUser($data)
	{
		return Db::table(self::TB_ADMIN_USER) -> insert($data);
	}

	//根据Ids批量删除管理员
	public function deleteAdminUserByIds($ids)
	{
		return Db::table(self::TB_ADMIN_USER) -> where(self::TB_ADMIN_USER . '.id', 'in', $ids) -> delete();
	}

	//根据查询条件查询管理员
	public function searchAdminUser($condition = '')
	{
		$fields = [
			self::TB_ADMIN_USER . '.id as id',
			self::TB_ADMIN_USER . '.name as name',
			self::TB_ADMIN_USER . '.password as password',
			self::TB_ADMIN_USER . '.email as email',
			'FROM_UNIXTIME(' . self::TB_ADMIN_USER . '.register_time, "%Y-%m-%d %H:%i:%s") as registerTime',
			'FROM_UNIXTIME(' . self::TB_ADMIN_USER . '.login_time, "%Y-%m-%d %H:%i:%s") as loginTime',
		];

		if (isset($condition) && !empty($condition)) {
			Db::table(self::TB_ADMIN_USER) -> where(self::TB_ADMIN_USER . '.id', 'like', '%' . $condition . '%')
				-> whereOr(self::TB_ADMIN_USER . '.name', 'like', '%' . $condition . '%')
				-> whereOr(self::TB_ADMIN_USER . '.email', 'like', '%' . $condition . '%');
		}

		$result = Db::table(self::TB_ADMIN_USER)
			-> field($fields)
			-> order(self::TB_ADMIN_USER . '.id', 'asc')
			-> select();

		return $result = $result ? $result : "";
	}
}