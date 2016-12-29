<?php
namespace app\home\model;
use think\Model;
use think\Db;

class CommidityType extends Model
{
	const TB_COMMIDITY_TYPE = 'commidity_type';

	protected $pk = 'id';

	//获取商品子类别
	public function getCommidityTypeName($pId)
	{
		$fields = [
			self::TB_COMMIDITY_TYPE . '.id as id',
			self::TB_COMMIDITY_TYPE . '.name as name',
		];

		$result = Db::table(self::TB_COMMIDITY_TYPE)
			-> field($fields)
			-> where(self::TB_COMMIDITY_TYPE . '.p_id', $pId)
			-> select();

		return $result = $result ? $result : "";
	}
}