<?php
namespace app\admin\model;
use think\Model;
use think\Db;

class CommidityType extends Model
{
	const TB_COMMIDITY_TYPE   = 'commidity_type';
	const TB_COMMIDITY_PARENT = 'commidity_parent';
	const TB_ADMIN_USER       = 'admin_user';

	//获取商品子类别信息
	public function getCommidityTypeInfo($perPage = 10)
	{
		$fields = [
			self::TB_COMMIDITY_TYPE . '.id as id',
			self::TB_COMMIDITY_TYPE . '.name as name',
			self::TB_COMMIDITY_PARENT . '.id as comParentId',
			self::TB_COMMIDITY_PARENT . '.name as comParentName',
			'user1.name as createBy',
			'FROM_UNIXTIME(' . self::TB_COMMIDITY_TYPE . '.create_time, "%Y-%m-%d %H:%i:%s") as createTime',
			'user2.name as updateBy',
			'FROM_UNIXTIME(' . self::TB_COMMIDITY_TYPE . '.update_time, "%Y-%m-%d %H:%i:%s") as updateTime',
		];
		$total = $this -> getCommidityTypeInfoTotal();

		$result = Db::table(self::TB_COMMIDITY_TYPE)
			-> field($fields)
			-> join(self::TB_COMMIDITY_PARENT, self::TB_COMMIDITY_TYPE . '.p_id = ' . self::TB_COMMIDITY_PARENT . '.id', 'left')
			-> join(self::TB_ADMIN_USER . ' user1', 'user1.id = ' . self::TB_COMMIDITY_TYPE . '.create_by', 'left')
			-> join(self::TB_ADMIN_USER . ' user2', 'user2.id = ' . self::TB_COMMIDITY_TYPE . '.update_by', 'left')
			-> order(self::TB_COMMIDITY_TYPE . '.id', 'asc')
			-> paginate($perPage, $total);

		return $result = $result ? $result : "";
	}

	//获取商品子类别信息总数
	public function getCommidityTypeInfoTotal()
	{
		$fields = [
			self::TB_COMMIDITY_TYPE . '.id as id',
			self::TB_COMMIDITY_TYPE . '.name as name',
			self::TB_COMMIDITY_PARENT . '.name as comParentName',
			'user1.name as createBy',
			'FROM_UNIXTIME(' . self::TB_COMMIDITY_TYPE . '.create_time, "%Y-%m-%d %H:%i:%s") as createTime',
			'user2.name as updateBy',
			'FROM_UNIXTIME(' . self::TB_COMMIDITY_TYPE . '.update_time, "%Y-%m-%d %H:%i:%s") as updateTime',
		];

		$result = Db::table(self::TB_COMMIDITY_TYPE)
			-> field($fields)
			-> join(self::TB_COMMIDITY_PARENT, self::TB_COMMIDITY_TYPE . '.p_id = ' . self::TB_COMMIDITY_PARENT . '.id', 'left')
			-> join(self::TB_ADMIN_USER . ' user1', 'user1.id = ' . self::TB_COMMIDITY_TYPE . '.create_by', 'left')
			-> join(self::TB_ADMIN_USER . ' user2', 'user2.id = ' . self::TB_COMMIDITY_TYPE . '.update_by', 'left')
			-> order(self::TB_COMMIDITY_TYPE . '.id', 'asc')
			-> count();

		return $result = $result ? $result : "";
	}

	//根据ID删除商品类别信息
	public function deleteCommidityTypeById($id)
	{
		return Db::table(self::TB_COMMIDITY_TYPE) -> where(self::TB_COMMIDITY_TYPE . '.id', $id) -> delete();
	}

	//根据ID更新商品类别信息
	public function updateCommidityTypeById($id, $data)
	{
		return Db::table(self::TB_COMMIDITY_TYPE) -> where(self::TB_COMMIDITY_TYPE . '.id', $id) -> update($data);
	}

	//添加商品父类型
	public function addCommidityType($data)
	{
		return Db::table(self::TB_COMMIDITY_TYPE) -> insert($data);
	}

	//根据Ids批量删除商品类型
	public function deleteCommidityTypeByIds($ids)
	{
		return Db::table(self::TB_COMMIDITY_TYPE) -> where(self::TB_COMMIDITY_TYPE . '.id', 'in', $ids) -> delete();
	}

	//根据查询条件查询商品类型
	public function searchCommidityType($condition = '')
	{
		$fields = [
			self::TB_COMMIDITY_TYPE . '.id as id',
			self::TB_COMMIDITY_TYPE . '.name as name',
			self::TB_COMMIDITY_PARENT . '.name as comParentName',
			'user1.name as createBy',
			'FROM_UNIXTIME(' . self::TB_COMMIDITY_TYPE . '.create_time, "%Y-%m-%d %H:%i:%s") as createTime',
			'user2.name as updateBy',
			'FROM_UNIXTIME(' . self::TB_COMMIDITY_TYPE . '.update_time, "%Y-%m-%d %H:%i:%s") as updateTime',
		];

		if (isset($condition) && !empty($condition)) {
			Db::table(self::TB_COMMIDITY_TYPE) -> where(self::TB_COMMIDITY_TYPE . '.id', 'like', '%' . $condition . '%')
				-> whereOr(self::TB_COMMIDITY_TYPE . '.name', 'like', '%' . $condition . '%')
				-> whereOr(self::TB_COMMIDITY_PARENT . '.name', 'like', '%' . $condition . '%')
				-> whereOr('user1.name', 'like', '%' . $condition . '%')
				-> whereOr('user2.name', 'like', '%' . $condition . '%');
		}

		$result = Db::table(self::TB_COMMIDITY_TYPE)
			-> field($fields)
			-> join(self::TB_COMMIDITY_PARENT, self::TB_COMMIDITY_TYPE . '.p_id = ' . self::TB_COMMIDITY_PARENT . '.id', 'left')
			-> join(self::TB_ADMIN_USER . ' user1', 'user1.id = ' . self::TB_COMMIDITY_TYPE . '.create_by', 'left')
			-> join(self::TB_ADMIN_USER . ' user2', 'user2.id = ' . self::TB_COMMIDITY_TYPE . '.update_by', 'left')
			-> order(self::TB_COMMIDITY_TYPE . '.id', 'asc')
			-> select();

		return $result = $result ? $result : "";
	}

	//获取商品类型的ＩＤ和名称
	public function getCommidityTypeIdAndName()
	{
		$fields = [
			self::TB_COMMIDITY_TYPE . '.id as comTypeId',
			self::TB_COMMIDITY_TYPE . '.name as comTypeName',
		];

		$result = DB::table(self::TB_COMMIDITY_TYPE)
			-> field($fields)
			-> select();

		return $result = $result ? $result : "";
	}
}