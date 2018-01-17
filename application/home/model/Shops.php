<?php
namespace app\home\model;
use think\Model;
use think\Db;

class Shops extends Model
{
	const TB_SHOPS            = 'shops';
	const TB_COMMIDITY        = 'commidity';
	const TB_COMMIDITY_TYPE   = 'commidity_type';
	const TB_COMMIDITY_PARENT = 'commidity_parent';
	const TB_BRAND            = 'brand';

	//构造函数
	public function __construct()
	{
		parent::__construct();
	}

	//获取所有商店对应信息
	public function getShopsInfo($perPage = 10, $total)
	{
		$fields = [
			self::TB_SHOPS . '.id as id',
			self::TB_SHOPS . '.name as name',
			self::TB_SHOPS . '.sevices as sevices',
			self::TB_SHOPS . '.description as description',
			self::TB_SHOPS . '.face as face',
			self::TB_SHOPS . '.license as license',
			self::TB_SHOPS . '.address as address',
			self::TB_SHOPS . '.mobile as mobile',
			self::TB_SHOPS . '.qq as qq',
			self::TB_SHOPS . '.alipay as alipay',
			self::TB_SHOPS . '.wxpay as wxpay',
			self::TB_SHOPS . '.longtitude as longtitude',
			self::TB_SHOPS . '.latitude as latitude',
			self::TB_SHOPS . '.credit as credit',
			self::TB_SHOPS . '.on_duty as onDuty',
			self::TB_SHOPS . '.off_duty as offDuty',
			'FROM_UNIXTIME(' . self::TB_SHOPS . '.create_time, "%Y-%m-%d %H:%i:%s") as createTime',
			'FROM_UNIXTIME(' . self::TB_SHOPS . '.update_time, "%Y-%m-%d %H:%i:%s") as updateTime',
		];

		$result = Db::table(self::TB_SHOPS)
			-> field($fields)
			-> paginate($perPage, $total);

		return $result = $result ? $result : "";
	}

	//获取所有商店对应信息总数
	public function getShopsInfoTotal()
	{
		$fields = [
			self::TB_SHOPS . '.id as id',
			self::TB_SHOPS . '.name as name',
			self::TB_SHOPS . '.sevices as sevices',
			self::TB_SHOPS . '.description as description',
			self::TB_SHOPS . '.face as face',
			self::TB_SHOPS . '.license as license',
			self::TB_SHOPS . '.address as address',
			self::TB_SHOPS . '.mobile as mobile',
			self::TB_SHOPS . '.qq as qq',
			self::TB_SHOPS . '.alipay as alipay',
			self::TB_SHOPS . '.wxpay as wxpay',
			self::TB_SHOPS . '.longtitude as longtitude',
			self::TB_SHOPS . '.latitude as latitude',
			self::TB_SHOPS . '.credit as credit',
			self::TB_SHOPS . '.on_duty as onDuty',
			self::TB_SHOPS . '.off_duty as offDuty',
			'FROM_UNIXTIME(' . self::TB_SHOPS . '.create_time, "%Y-%m-%d %H:%i:%s") as createTime',
			'FROM_UNIXTIME(' . self::TB_SHOPS . '.update_time, "%Y-%m-%d %H:%i:%s") as updateTime',
		];

		$result = Db::table(self::TB_SHOPS)
			-> field($fields)
			-> count();

		return $result = $result ? $result : "";
	}

	//根据商店ID获取商店下的产品信息
	public function getCommiditiesInfoByShopId($id, $perPage = 10, $total)
	{
		$fields = [
			self::TB_SHOPS . '.id as id',
			self::TB_SHOPS . '.name as name',
			self::TB_SHOPS . '.sevices as sevices',
			self::TB_SHOPS . '.description as description',
			self::TB_SHOPS . '.face as face',
			self::TB_SHOPS . '.license as license',
			self::TB_SHOPS . '.address as address',
			self::TB_SHOPS . '.mobile as mobile',
			self::TB_SHOPS . '.qq as qq',
			self::TB_SHOPS . '.alipay as alipay',
			self::TB_SHOPS . '.wxpay as wxpay',
			self::TB_SHOPS . '.longtitude as longtitude',
			self::TB_SHOPS . '.latitude as latitude',
			self::TB_SHOPS . '.credit as credit',
			self::TB_SHOPS . '.on_duty as onDuty',
			self::TB_SHOPS . '.off_duty as offDuty',
			'FROM_UNIXTIME(' . self::TB_SHOPS . '.create_time, "%Y-%m-%d %H:%i:%s") as createTime',
			'FROM_UNIXTIME(' . self::TB_SHOPS . '.update_time, "%Y-%m-%d %H:%i:%s") as updateTime',
			self::TB_COMMIDITY . '.id as commidityId',
			self::TB_COMMIDITY . '.name as commidityName',
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

		$result = Db::table(self::TB_SHOPS)
			-> field($fields)
			-> join(self::TB_COMMIDITY, self::TB_COMMIDITY . '.shop_id = ' . self::TB_SHOPS . '.id', 'left')
			-> join(self::TB_COMMIDITY_TYPE, self::TB_COMMIDITY_TYPE . '.id = ' . self::TB_COMMIDITY . '.commidity_type_id', 'left')
			-> join(self::TB_COMMIDITY_PARENT, self::TB_COMMIDITY_PARENT . '.id = ' . self::TB_COMMIDITY_TYPE . '.p_id', 'left')
			-> join(self::TB_BRAND, self::TB_BRAND . '.id = ' . self::TB_COMMIDITY . '.brand_id', 'left')
			-> where(self::TB_COMMIDITY . '.is_new', 1)
			-> where(self::TB_SHOPS . '.id', $id)
			-> order('id', 'desc')
			-> paginate($perPage, $total);

		return $result = $result ? $result : '';
	}

	//根据商店ID获取商店下的产品信息总数
	public function getCommiditiesInfoByShopIdTotal($id)
	{
		$fields = [
			self::TB_SHOPS . '.id as id',
			self::TB_SHOPS . '.name as name',
			self::TB_SHOPS . '.sevices as sevices',
			self::TB_SHOPS . '.description as description',
			self::TB_SHOPS . '.face as face',
			self::TB_SHOPS . '.license as license',
			self::TB_SHOPS . '.address as address',
			self::TB_SHOPS . '.mobile as mobile',
			self::TB_SHOPS . '.qq as qq',
			self::TB_SHOPS . '.alipay as alipay',
			self::TB_SHOPS . '.wxpay as wxpay',
			self::TB_SHOPS . '.longtitude as longtitude',
			self::TB_SHOPS . '.latitude as latitude',
			self::TB_SHOPS . '.credit as credit',
			self::TB_SHOPS . '.on_duty as onDuty',
			self::TB_SHOPS . '.off_duty as offDuty',
			'FROM_UNIXTIME(' . self::TB_SHOPS . '.create_time, "%Y-%m-%d %H:%i:%s") as createTime',
			'FROM_UNIXTIME(' . self::TB_SHOPS . '.update_time, "%Y-%m-%d %H:%i:%s") as updateTime',
			self::TB_COMMIDITY . '.id as commidityId',
			self::TB_COMMIDITY . '.name as commidityName',
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

		$result = Db::table(self::TB_SHOPS)
			-> field($fields)
			-> join(self::TB_COMMIDITY, self::TB_COMMIDITY . '.shop_id = ' . self::TB_SHOPS . '.id', 'left')
			-> join(self::TB_COMMIDITY_TYPE, self::TB_COMMIDITY_TYPE . '.id = ' . self::TB_COMMIDITY . '.commidity_type_id', 'left')
			-> join(self::TB_COMMIDITY_PARENT, self::TB_COMMIDITY_PARENT . '.id = ' . self::TB_COMMIDITY_TYPE . '.p_id', 'left')
			-> join(self::TB_BRAND, self::TB_BRAND . '.id = ' . self::TB_COMMIDITY . '.brand_id', 'left')
			-> where(self::TB_COMMIDITY . '.is_new', 1)
			-> where(self::TB_SHOPS . '.id', $id)
			-> count();

		return $result = $result ? $result : '';
	}


	//根据商店ID获取商店信息
	public function getShopInfoByShopId($id)
	{
		$fields = [
			self::TB_SHOPS . '.id as id',
			self::TB_SHOPS . '.name as name',
			self::TB_SHOPS . '.sevices as sevices',
			self::TB_SHOPS . '.description as description',
			self::TB_SHOPS . '.face as face',
			self::TB_SHOPS . '.license as license',
			self::TB_SHOPS . '.address as address',
			self::TB_SHOPS . '.mobile as mobile',
			self::TB_SHOPS . '.qq as qq',
			self::TB_SHOPS . '.alipay as alipay',
			self::TB_SHOPS . '.wxpay as wxpay',
			self::TB_SHOPS . '.longtitude as longtitude',
			self::TB_SHOPS . '.latitude as latitude',
			self::TB_SHOPS . '.credit as credit',
			self::TB_SHOPS . '.on_duty as onDuty',
			self::TB_SHOPS . '.off_duty as offDuty',
			'FROM_UNIXTIME(' . self::TB_SHOPS . '.create_time, "%Y-%m-%d %H:%i:%s") as createTime',
			'FROM_UNIXTIME(' . self::TB_SHOPS . '.update_time, "%Y-%m-%d %H:%i:%s") as updateTime',
		];

		$result = Db::table(self::TB_SHOPS)
			-> field($fields)
			-> where(self::TB_SHOPS . '.id', $id)
			-> find();

		return $result = $result ? $result : '';
	}

	//根据搜索条件查询店铺信息
	public function getShopSInfoByCondition($condition, $perPage = 10, $total)
	{
		$fields = [
			self::TB_SHOPS . '.id as id',
			self::TB_SHOPS . '.name as name',
			self::TB_SHOPS . '.sevices as sevices',
			self::TB_SHOPS . '.description as description',
			self::TB_SHOPS . '.face as face',
			self::TB_SHOPS . '.license as license',
			self::TB_SHOPS . '.address as address',
			self::TB_SHOPS . '.mobile as mobile',
			self::TB_SHOPS . '.qq as qq',
			self::TB_SHOPS . '.alipay as alipay',
			self::TB_SHOPS . '.wxpay as wxpay',
			self::TB_SHOPS . '.longtitude as longtitude',
			self::TB_SHOPS . '.latitude as latitude',
			self::TB_SHOPS . '.credit as credit',
			self::TB_SHOPS . '.on_duty as onDuty',
			self::TB_SHOPS . '.off_duty as offDuty',
			'FROM_UNIXTIME(' . self::TB_SHOPS . '.create_time, "%Y-%m-%d %H:%i:%s") as createTime',
			'FROM_UNIXTIME(' . self::TB_SHOPS . '.update_time, "%Y-%m-%d %H:%i:%s") as updateTime',
		];

		if ($condition > 4) {
			$result = Db::table(self::TB_SHOPS)
				-> field($fields)
				-> where(self::TB_SHOPS . '.credit', '>', $condition)
				-> order('id', 'desc')
				-> paginate($perPage, $total);
		} else {
			$result = Db::table(self::TB_SHOPS)
				-> field($fields)
				-> where(self::TB_SHOPS . '.type', $condition)
				-> order('id', 'desc')
				-> paginate($perPage, $total);
		}

		return $result = $result ? $result : '';
	}

	//根据搜索条件查询店铺信息总数
	public function getShopSInfoByConditionTotal($condition)
	{
		$fields = [
			self::TB_SHOPS . '.id as id',
			self::TB_SHOPS . '.name as name',
			self::TB_SHOPS . '.sevices as sevices',
			self::TB_SHOPS . '.description as description',
			self::TB_SHOPS . '.face as face',
			self::TB_SHOPS . '.license as license',
			self::TB_SHOPS . '.address as address',
			self::TB_SHOPS . '.mobile as mobile',
			self::TB_SHOPS . '.qq as qq',
			self::TB_SHOPS . '.alipay as alipay',
			self::TB_SHOPS . '.wxpay as wxpay',
			self::TB_SHOPS . '.longtitude as longtitude',
			self::TB_SHOPS . '.latitude as latitude',
			self::TB_SHOPS . '.credit as credit',
			self::TB_SHOPS . '.on_duty as onDuty',
			self::TB_SHOPS . '.off_duty as offDuty',
			'FROM_UNIXTIME(' . self::TB_SHOPS . '.create_time, "%Y-%m-%d %H:%i:%s") as createTime',
			'FROM_UNIXTIME(' . self::TB_SHOPS . '.update_time, "%Y-%m-%d %H:%i:%s") as updateTime',
		];

		$result = Db::table(self::TB_SHOPS)
			-> field($fields)
			-> where(self::TB_SHOPS . '.type', $condition)
			-> count();

		return $result = $result ? $result : '';
	}
}