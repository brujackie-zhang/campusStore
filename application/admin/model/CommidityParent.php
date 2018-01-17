<?php
namespace app\admin\model;
use think\Model;
use think\Db;

class CommidityParent extends Model
{
	const TB_COMMIDITY_PARENT = 'commidity_parent';
	const TB_ADMIN_USER       = 'admin_user';

	//获取商品父类别信息
	public function getCommidityParentInfo($perPage = 10)
	{
		$fields = [
			self::TB_COMMIDITY_PARENT . '.id as id',
			self::TB_COMMIDITY_PARENT . '.name as name',
			'user1.name as createBy',
			'FROM_UNIXTIME(' . self::TB_COMMIDITY_PARENT . '.create_time, "%Y-%m-%d %H:%i:%s") as createTime',
			'user2.name as updateBy',
			'FROM_UNIXTIME(' . self::TB_COMMIDITY_PARENT . '.update_time, "%Y-%m-%d %H:%i:%s") as updateTime',
		];

		$total = $this -> getCommidityParentInfoTotal();

		$result = Db::table(self::TB_COMMIDITY_PARENT)
			-> field($fields)
			-> join(self::TB_ADMIN_USER . ' user1', 'user1.id = ' . self::TB_COMMIDITY_PARENT . '.create_by', 'left')
			-> join(self::TB_ADMIN_USER . ' user2', 'user2.id = ' . self::TB_COMMIDITY_PARENT . '.update_by', 'left')
			-> order(self::TB_COMMIDITY_PARENT . '.id', 'asc')
			-> paginate($perPage, $total);

		return $result = $result ? $result : '';
	}

	//获取商品父类别信息总数
	public function getCommidityParentInfoTotal()
	{
		$fields = [
			self::TB_COMMIDITY_PARENT . '.id as id',
			self::TB_COMMIDITY_PARENT . '.name as name',
			'user1.name as createBy',
			'FROM_UNIXTIME(' . self::TB_COMMIDITY_PARENT . '.create_time, "%Y-%m-%d %H:%i:%s") as createTime',
			'user2.name as updateBy',
			'FROM_UNIXTIME(' . self::TB_COMMIDITY_PARENT . '.update_time, "%Y-%m-%d %H:%i:%s") as updateTime',
		];

		$result = Db::table(self::TB_COMMIDITY_PARENT)
			-> field($fields)
			-> join(self::TB_ADMIN_USER . ' user1', 'user1.id = ' . self::TB_COMMIDITY_PARENT . '.create_by', 'left')
			-> join(self::TB_ADMIN_USER . ' user2', 'user2.id = ' . self::TB_COMMIDITY_PARENT . '.update_by', 'left')
			-> order(self::TB_COMMIDITY_PARENT . '.id', 'asc')
			-> count();

		return $result = $result ? $result : '';
	}

	//根据ID删除商品父类别信息
	public function deleteCommidityParentById($id)
	{
		return Db::table(self::TB_COMMIDITY_PARENT) -> where(self::TB_COMMIDITY_PARENT . '.id', $id) -> delete();
	}

	//根据ID更新商品父类别信息
	public function updateCommidityParentById($id, $data)
	{
		return Db::table(self::TB_COMMIDITY_PARENT) -> where(self::TB_COMMIDITY_PARENT . '.id', $id) -> update($data);
	}

	//添加商品父类型
	public function addCommidityParent($data)
	{
		return Db::table(self::TB_COMMIDITY_PARENT) -> insert($data);
	}

	//根据Ids批量删除商品父类型
	public function deleteCommidityParentByIds($ids)
	{
		return Db::table(self::TB_COMMIDITY_PARENT) -> where(self::TB_COMMIDITY_PARENT . '.id', 'in', $ids) -> delete();
	}

	//根据查询条件查询商品父类型
	public function searchCommidityParent($condition = '')
	{
		$fields = [
			self::TB_COMMIDITY_PARENT . '.id as id',
			self::TB_COMMIDITY_PARENT . '.name as name',
			'user1.name as createBy',
			'FROM_UNIXTIME(' . self::TB_COMMIDITY_PARENT . '.create_time, "%Y-%m-%d %H:%i:%s") as createTime',
			'user2.name as updateBy',
			'FROM_UNIXTIME(' . self::TB_COMMIDITY_PARENT . '.update_time, "%Y-%m-%d %H:%i:%s") as updateTime',
		];

		if (isset($condition) && !empty($condition)) {
			Db::table(self::TB_COMMIDITY_PARENT) -> where(self::TB_COMMIDITY_PARENT . '.id', 'like', '%' . $condition . '%')
				-> whereOr(self::TB_COMMIDITY_PARENT . '.name', 'like', '%' . $condition . '%')
				-> whereOr('user1.name', 'like', '%' . $condition . '%')
				-> whereOr('user2.name', 'like', '%' . $condition . '%');
		}

		$result = Db::table(self::TB_COMMIDITY_PARENT)
			-> field($fields)
			-> join(self::TB_ADMIN_USER . ' user1', 'user1.id = ' . self::TB_COMMIDITY_PARENT . '.create_by', 'left')
			-> join(self::TB_ADMIN_USER . ' user2', 'user2.id = ' . self::TB_COMMIDITY_PARENT . '.update_by', 'left')
			-> order(self::TB_COMMIDITY_PARENT . '.id', 'asc')
			-> select();

		return $result = $result ? $result : '';
	}

	//获取商品父类型的Id和名称
	public function getCommidityParentIdAndName()
	{
		$fields = [
			self::TB_COMMIDITY_PARENT . '.id as commidityParentId',
			self::TB_COMMIDITY_PARENT . '.name as commidityParentName',
		];

		$result = Db::table(self::TB_COMMIDITY_PARENT)
			-> field($fields)
			-> order(self::TB_COMMIDITY_PARENT . '.id', 'asc')
			-> select();

		return $result = $result ? $result : "";
	}
}