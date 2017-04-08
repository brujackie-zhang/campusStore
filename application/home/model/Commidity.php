<?php
namespace app\home\model;
use think\Model;
use think\Db;

class Commidity extends Model
{
	const TB_COMMIDITY        = 'commidity';
	const TB_COMMIDITY_TYPE   = 'commidity_type';
	const TB_COMMIDITY_PARENT = 'commidity_parent';
	const TB_BRAND            = 'brand';
	const TB_DEAL             = 'deal';
	const TB_USER             = 'user';

	protected $pk = 'id';

	//通过商品子类别获取商品信息
	public function getCommidityInfoByType($tId)
	{
		$fields = [
			self::TB_COMMIDITY. '.id as id',
			self::TB_COMMIDITY . '.name as name',
			self::TB_COMMIDITY . '.image as image',
		];

		$result = Db::table(self::TB_COMMIDITY)
			-> field($fields)
			-> where(self::TB_COMMIDITY . '.commidity_type_id', $tId)
			->select();

		return $result = $result ? $result : '';
	}

	//获取商品展示页商品信息
	// public function commidityInfoForIndex($nowPage = 1, $perPage = 12)
	public function commidityInfoForIndex($perPage = 12, $total)
	{
		$fields = [
			self::TB_COMMIDITY . '.id as id',
			self::TB_COMMIDITY . '.name as name',
			self::TB_COMMIDITY_TYPE . '.name as cTypeName',
			self::TB_COMMIDITY_PARENT . '.name as cParentName',
			self::TB_BRAND . '.name as brandName',
			self::TB_COMMIDITY . '.picture as picture',
			self::TB_COMMIDITY . '.image as image',
			self::TB_COMMIDITY . '.introduction as introduction',
			self::TB_COMMIDITY . '.sales_volume as salesVolume',
			self::TB_COMMIDITY . '.price as price',
			self::TB_COMMIDITY . '.extra_info as extraInfo',
		];

		$result = Db::table(self::TB_COMMIDITY)
			-> field($fields)
			-> join(self::TB_COMMIDITY_TYPE, self::TB_COMMIDITY_TYPE . '.id = ' . self::TB_COMMIDITY . '.commidity_type_id', 'left')
			-> join(self::TB_COMMIDITY_PARENT, self::TB_COMMIDITY_PARENT . '.id = ' . self::TB_COMMIDITY_TYPE . '.p_id', 'left')
			-> join(self::TB_BRAND, self::TB_BRAND . '.id = ' . self::TB_COMMIDITY . '.brand_id', 'left')
			// -> where($condition)
			-> where(self::TB_COMMIDITY . '.is_new', 1)
			// -> limit($nowPage, $perPage)
			// -> select();
			-> order('id', 'desc')
			-> paginate($perPage, $total);

		return $result = $result ? $result : '';
	}

	//获取商品展示页商品总数
	public function getCommidityTotal()
	{
		$fields = [
			self::TB_COMMIDITY . '.id as id',
			self::TB_COMMIDITY . '.name as name',
			self::TB_COMMIDITY_TYPE . '.name as cTypeName',
			self::TB_COMMIDITY_PARENT . '.name as cParentName',
			self::TB_BRAND . '.name as brandName',
			self::TB_COMMIDITY . '.picture as picture',
			self::TB_COMMIDITY . '.image as image',
			self::TB_COMMIDITY . '.introduction as introduction',
			self::TB_COMMIDITY . '.sales_volume as salesVolume',
			self::TB_COMMIDITY . '.price as price',
			self::TB_COMMIDITY . '.extra_info as extraInfo',
		];

		$result = Db::table(self::TB_COMMIDITY)
			-> field($fields)
			-> join(self::TB_COMMIDITY_TYPE, self::TB_COMMIDITY_TYPE . '.id = ' . self::TB_COMMIDITY . '.commidity_type_id', 'left')
			-> join(self::TB_COMMIDITY_PARENT, self::TB_COMMIDITY_PARENT . '.id = ' . self::TB_COMMIDITY_TYPE . '.p_id', 'left')
			-> join(self::TB_BRAND, self::TB_BRAND . '.id = ' . self::TB_COMMIDITY . '.brand_id', 'left')
			// -> where($condition)
			-> where(self::TB_COMMIDITY . '.is_new', 1)
			-> count();

		return $result = $result ? $result : '';
	}

	//根据商品ID获取商品详细信息
	public function getCommidityInfoByCommidityId($commidityId)
	{
		$fields = [
			self::TB_COMMIDITY . '.id as commidityId',
			self::TB_COMMIDITY . '.name as name',
			self::TB_COMMIDITY_TYPE . '.name as cTypeName',
			self::TB_COMMIDITY_PARENT . '.name as cParentName',
			self::TB_BRAND . '.name as brandName',
			self::TB_COMMIDITY . '.picture as picture',
			self::TB_COMMIDITY . '.image as image',
			self::TB_COMMIDITY . '.introduction as introduction',
			self::TB_COMMIDITY . '.stocks as stocks',
			self::TB_COMMIDITY . '.sales_volume as salesVolume',
			self::TB_COMMIDITY . '.price as price',
			self::TB_COMMIDITY . '.discount as discount',
			self::TB_COMMIDITY . '.extra_info as extraInfo',
		];

		$result = Db::table(self::TB_COMMIDITY)
			-> field($fields)
			-> join(self::TB_COMMIDITY_TYPE, self::TB_COMMIDITY_TYPE . '.id = ' . self::TB_COMMIDITY . '.commidity_type_id', 'left')
			-> join(self::TB_COMMIDITY_PARENT, self::TB_COMMIDITY_PARENT . '.id = ' . self::TB_COMMIDITY_TYPE . '.p_id', 'left')
			-> join(self::TB_BRAND, self::TB_BRAND . '.id = ' . self::TB_COMMIDITY . '.brand_id', 'left')
			// -> where($condition)
			-> where(self::TB_COMMIDITY . '.id', $commidityId)
			-> select();

		return $result = $result ? $result : '';
	}

	//获取购买过该商品的用户
	public function getUserIdByCommidityId($commidityId)
	{
		$fields = [
			self::TB_DEAL . '.user_id as userId',
		];

		$result = Db::table(self::TB_DEAL)
			-> field($fields)
			-> where(self::TB_DEAL . '.commidity_id', $commidityId)
			-> select();

		return $result = $result ? $result : '';
	}

	//根据商品ID 从订单中获取买过该商品的用户还买过的商品信息
	public function getCommiditiesInfoFromDeal($userIds, $commidityId)
	{
		$fields = [
			self::TB_COMMIDITY . '.id as commidityId',
			self::TB_USER . '.id as userId',
			self::TB_COMMIDITY . '.name as name',
			self::TB_COMMIDITY . '.introduction as introduction',
			self::TB_COMMIDITY . '.price as price',
			self::TB_COMMIDITY . '.image as image',
		];

		$result = Db::table(self::TB_DEAL)
			-> field($fields)
			-> join(self::TB_COMMIDITY, self::TB_COMMIDITY . '.id = ' . self::TB_DEAL . '.commidity_id', 'left')
			-> join(self::TB_USER, self::TB_USER . '.id = ' . self::TB_DEAL . '.user_id', 'left')
			-> where(self::TB_DEAL . '.user_id', 'in', $userIds)
			-> where(self::TB_DEAL . '.commidity_id != ' . $commidityId)
			-> limit(6)
			-> order(self::TB_DEAL . '.id', 'desc')
			-> select();

		return $result = $result ? $result : "";
	}

	//根据商品父类ID获取商品信息
	public function getCommiditiesByParentId($pId, $perPage = 10, $total, $condition = '')
	{
		$fields = [
			self::TB_COMMIDITY . '.id as id',
			self::TB_COMMIDITY . '.name as name',
			self::TB_COMMIDITY_TYPE . '.name as cTypeName',
			self::TB_COMMIDITY_PARENT . '.name as cParentName',
			self::TB_BRAND . '.name as brandName',
			self::TB_COMMIDITY . '.picture as picture',
			self::TB_COMMIDITY . '.image as image',
			self::TB_COMMIDITY . '.introduction as introduction',
			self::TB_COMMIDITY . '.sales_volume as salesVolume',
			self::TB_COMMIDITY . '.price as price',
			self::TB_COMMIDITY . '.extra_info as extraInfo',
		];

		if (isset($condition) && ! empty($condition)) {
			Db::table(self::TB_COMMIDITY) -> where(self::TB_COMMIDITY . '.name', 'like', "%" . $condition . "%")
				-> whereOr(self::TB_COMMIDITY_TYPE . '.name', 'like', "%" . $condition . "%")
				-> whereOr(self::TB_COMMIDITY_PARENT . '.name', 'like', "%" . $condition . "%");
		}

		$result = Db::table(self::TB_COMMIDITY)
			-> field($fields)
			-> join(self::TB_COMMIDITY_TYPE, self::TB_COMMIDITY_TYPE . '.id = ' . self::TB_COMMIDITY . '.commidity_type_id', 'left')
			-> join(self::TB_COMMIDITY_PARENT, self::TB_COMMIDITY_PARENT . '.id = ' . self::TB_COMMIDITY_TYPE . '.p_id', 'left')
			-> join(self::TB_BRAND, self::TB_BRAND . '.id = ' . self::TB_COMMIDITY . '.brand_id', 'left')
			// -> where($condition)
			-> where(self::TB_COMMIDITY_PARENT . '.id = ' . $pId)
			// -> limit($nowPage, $perPage)
			// -> select();
			-> order(self::TB_COMMIDITY . '.id', 'desc')
			-> paginate($perPage, $total);

		return $result = $result ? $result : '';
	}

	//根据商品父类ID获取商品总数
	public function getCommiditiesByParentIdTotal($pId, $condition = '')
	{
		$fields = [
			self::TB_COMMIDITY . '.id as id',
			self::TB_COMMIDITY . '.name as name',
			self::TB_COMMIDITY_TYPE . '.name as cTypeName',
			self::TB_COMMIDITY_PARENT . '.name as cParentName',
			self::TB_BRAND . '.name as brandName',
			self::TB_COMMIDITY . '.picture as picture',
			self::TB_COMMIDITY . '.image as image',
			self::TB_COMMIDITY . '.introduction as introduction',
			self::TB_COMMIDITY . '.sales_volume as salesVolume',
			self::TB_COMMIDITY . '.price as price',
			self::TB_COMMIDITY . '.extra_info as extraInfo',
		];

		if (isset($condition) && ! empty($condition)) {
			Db::table(self::TB_COMMIDITY) -> where(self::TB_COMMIDITY . '.name', 'like', "%" . $condition . "%")
				-> whereOr(self::TB_COMMIDITY_TYPE . '.name', 'like', "%" . $condition . "%")
				-> whereOr(self::TB_COMMIDITY_PARENT . '.name', 'like', "%" . $condition . "%");
		}

		$result = Db::table(self::TB_COMMIDITY)
			-> field($fields)
			-> join(self::TB_COMMIDITY_TYPE, self::TB_COMMIDITY_TYPE . '.id = ' . self::TB_COMMIDITY . '.commidity_type_id', 'left')
			-> join(self::TB_COMMIDITY_PARENT, self::TB_COMMIDITY_PARENT . '.id = ' . self::TB_COMMIDITY_TYPE . '.p_id', 'left')
			-> join(self::TB_BRAND, self::TB_BRAND . '.id = ' . self::TB_COMMIDITY . '.brand_id', 'left')
			// -> where($condition)
			-> where(self::TB_COMMIDITY_PARENT . '.id = ' . $pId)
			-> count();

		return $result = $result ? $result : '';
	}
}