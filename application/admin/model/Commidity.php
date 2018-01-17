<?php
namespace app\admin\model;
use think\Model;
use think\Db;

class Commidity extends Model
{
	const TB_COMMIDITY        = 'commidity';
	const TB_COMMIDITY_TYPE   = 'commidity_type';
	const TB_COMMIDITY_PARENT = 'commidity_parent';
	const TB_ADMIN_USER       = 'admin_user';

	//获取商品信息
	public function getCommidityInfo($perPage = 10)
	{
		$fields = [
			self::TB_COMMIDITY . '.id as id',
			self::TB_COMMIDITY . '.name as name',
			self::TB_COMMIDITY_TYPE . '.id as cTypeId',
			self::TB_COMMIDITY_TYPE . '.name as cTypeName',
			self::TB_COMMIDITY_PARENT . '.name as cParentName',
			self::TB_COMMIDITY . '.picture as picture',
			self::TB_COMMIDITY . '.image as image',
			self::TB_COMMIDITY . '.introduction as introduction',
			self::TB_COMMIDITY . '.stocks as stocks',
			self::TB_COMMIDITY . '.sales_volume as salesVolume',
			self::TB_COMMIDITY . '.price as price',
			self::TB_COMMIDITY . '.discount as discount',
			self::TB_COMMIDITY . '.is_new as isNew',
			self::TB_COMMIDITY . '.extra_info as extraInfo',
			'user1.name as createBy',
			'FROM_UNIXTIME(' . self::TB_COMMIDITY . '.create_time, "%Y-%m-%d %H:%i:%s") as createTime',
			'user2.name as updateBy',
			'FROM_UNIXTIME(' . self::TB_COMMIDITY . '.update_time, "%Y-%m-%d %H:%i:%s") as updateTime',
		];
		$total = $this -> getCommidityInfoTotal();

		$result = Db::table(self::TB_COMMIDITY)
			-> field($fields)
			-> join(self::TB_COMMIDITY_TYPE, self::TB_COMMIDITY_TYPE . '.id = ' . self::TB_COMMIDITY . '.commidity_type_id', 'left')
			-> join(self::TB_COMMIDITY_PARENT, self::TB_COMMIDITY_TYPE . '.p_id = ' . self::TB_COMMIDITY_PARENT . '.id', 'left')
			-> join(self::TB_ADMIN_USER . ' user1', 'user1.id = ' . self::TB_COMMIDITY . '.create_by', 'left')
			-> join(self::TB_ADMIN_USER . ' user2', 'user2.id = ' . self::TB_COMMIDITY . '.update_by', 'left')
			-> order(self::TB_COMMIDITY . '.id', 'asc')
			-> paginate($perPage, $total);

		return $result = $result ? $result : "";
	}

	//获取商品信息总数
	public function getCommidityInfoTotal()
	{
		$fields = [
			self::TB_COMMIDITY . '.id as id',
			self::TB_COMMIDITY . '.name as name',
			self::TB_COMMIDITY_TYPE . '.id as cTypeId',
			self::TB_COMMIDITY_TYPE . '.name as cTypeName',
			self::TB_COMMIDITY_PARENT . '.name as cParentName',
			self::TB_COMMIDITY . '.picture as picture',
			self::TB_COMMIDITY . '.image as image',
			self::TB_COMMIDITY . '.introduction as introduction',
			self::TB_COMMIDITY . '.stocks as stocks',
			self::TB_COMMIDITY . '.sales_volume as salesVolume',
			self::TB_COMMIDITY . '.price as price',
			self::TB_COMMIDITY . '.discount as discount',
			self::TB_COMMIDITY . '.is_new as isNew',
			self::TB_COMMIDITY . '.extra_info as extraInfo',
			'user1.name as createBy',
			'FROM_UNIXTIME(' . self::TB_COMMIDITY . '.create_time, "%Y-%m-%d %H:%i:%s") as createTime',
			'user2.name as updateBy',
			'FROM_UNIXTIME(' . self::TB_COMMIDITY . '.update_time, "%Y-%m-%d %H:%i:%s") as updateTime',
		];

		$result = Db::table(self::TB_COMMIDITY)
			-> field($fields)
			-> join(self::TB_COMMIDITY_TYPE, self::TB_COMMIDITY_TYPE . '.id = ' . self::TB_COMMIDITY . '.commidity_type_id', 'left')
			-> join(self::TB_COMMIDITY_PARENT, self::TB_COMMIDITY_TYPE . '.p_id = ' . self::TB_COMMIDITY_PARENT . '.id', 'left')
			-> join(self::TB_ADMIN_USER . ' user1', 'user1.id = ' . self::TB_COMMIDITY . '.create_by', 'left')
			-> join(self::TB_ADMIN_USER . ' user2', 'user2.id = ' . self::TB_COMMIDITY . '.update_by', 'left')
			-> order(self::TB_COMMIDITY . '.id', 'asc')
			-> count();

		return $result = $result ? $result : "";
	}

	//根据ID删除商品类别信息
	public function deleteCommidityById($id)
	{
		return Db::table(self::TB_COMMIDITY) -> where(self::TB_COMMIDITY . '.id', $id) -> delete();
	}

	//根据ID更新商品类别信息
	public function updateCommidityById($id, $data)
	{
		return Db::table(self::TB_COMMIDITY) -> where(self::TB_COMMIDITY . '.id', $id) -> update($data);
	}

	//添加商品
	public function addCommidity($data)
	{
		return Db::table(self::TB_COMMIDITY) -> insert($data);
	}

	//根据Ids批量删除商品类型
	public function deleteCommidityByIds($ids)
	{
		return Db::table(self::TB_COMMIDITY) -> where(self::TB_COMMIDITY . '.id', 'in', $ids) -> delete();
	}

	//根据查询条件查询商品类型
	public function searchCommidity($condition = '')
	{
		$fields = [
			self::TB_COMMIDITY . '.id as id',
			self::TB_COMMIDITY . '.name as name',
			self::TB_COMMIDITY_TYPE . '.name as cTypeName',
			self::TB_COMMIDITY_PARENT . '.name as cParentName',
			self::TB_COMMIDITY . '.picture as picture',
			self::TB_COMMIDITY . '.image as image',
			self::TB_COMMIDITY . '.introduction as introduction',
			self::TB_COMMIDITY . '.stocks as stocks',
			self::TB_COMMIDITY . '.sales_volume as salesVolume',
			self::TB_COMMIDITY . '.price as price',
			self::TB_COMMIDITY . '.discount as discount',
			self::TB_COMMIDITY . '.is_new as isNew',
			self::TB_COMMIDITY . '.extra_info as extraInfo',
			'user1.name as createBy',
			'FROM_UNIXTIME(' . self::TB_COMMIDITY . '.create_time, "%Y-%m-%d %H:%i:%s") as createTime',
			'user2.name as updateBy',
			'FROM_UNIXTIME(' . self::TB_COMMIDITY . '.update_time, "%Y-%m-%d %H:%i:%s") as updateTime',
		];

		if (isset($condition) && !empty($condition)) {
			Db::table(self::TB_COMMIDITY) -> where(self::TB_COMMIDITY . '.id', 'like', '%' . $condition . '%')
				-> whereOr(self::TB_COMMIDITY . '.name', 'like', '%' . $condition . '%')
				-> whereOr(self::TB_COMMIDITY_TYPE . '.name', 'like', '%' . $condition . '%')
				-> whereOr('user1.name', 'like', '%' . $condition . '%')
				-> whereOr('user2.name', 'like', '%' . $condition . '%');
		}

		$result = Db::table(self::TB_COMMIDITY)
			-> field($fields)
			-> join(self::TB_COMMIDITY_TYPE, self::TB_COMMIDITY_TYPE . '.id = ' . self::TB_COMMIDITY . '.commidity_type_id', 'left')
			-> join(self::TB_COMMIDITY_PARENT, self::TB_COMMIDITY_TYPE . '.p_id = ' . self::TB_COMMIDITY_PARENT . '.id', 'left')
			-> join(self::TB_ADMIN_USER . ' user1', 'user1.id = ' . self::TB_COMMIDITY . '.create_by', 'left')
			-> join(self::TB_ADMIN_USER . ' user2', 'user2.id = ' . self::TB_COMMIDITY . '.update_by', 'left')
			-> order(self::TB_COMMIDITY . '.id', 'asc')
			-> select();

		return $result = $result ? $result : "";
	}

	//获取商品类型ＩＤ和名称
	public function getCommidityTypeIdAndName()
	{
		$fields = [
			self::TB_COMMIDITY_TYPE . '.id as comTypeId',
			self::TB_COMMIDITY_TYPE . '.name as comTypeName',
		];

		$result = Db::table(self::TB_COMMIDITY_TYPE)
			-> field($fields)
			-> select();

		return $result = $result ? $result : "";
	}
}