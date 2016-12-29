<?php
namespace app\home\model;
use think\Model;
use think\Db;

class Commidity extends Model
{
	const TB_COMMIDITY      = 'commidity';
	const TB_COMMIDITY_TYPE = 'commidity_type';
	const TB_BRAND          = 'brand';

	protected $pk = 'id';

	//通过商品子类别获取商品信息
	public function getCommidityInfoByType($tId)
	{
		$fields = [
			self::TB_COMMIDITY. '.id as id',
			self::TB_COMMIDITY . '.name as name',
		];

		$result = Db::table(self::TB_COMMIDITY)
			-> field($fields)
			-> where(self::TB_COMMIDITY . '.commidity_type_id', $tId)
			->select();

		return $result = $result ? $result : '';
	}

	//获取商品展示页商品信息
	public function commidityInfoForIndex()
	{
		$fields = [
			self::TB_COMMIDITY . '.id as id',
			self::TB_COMMIDITY . '.name as name',
			self::TB_COMMIDITY_TYPE . '.name as cTypeName',
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
			-> join(self::TB_BRAND, self::TB_BRAND . '.id = ' . self::TB_COMMIDITY . '.brand_id', 'left')
			-> where(self::TB_COMMIDITY . '.is_new', 1)
			-> where(self::TB_COMMIDITY . '.is_delete', 0)
			-> select();
	}
}