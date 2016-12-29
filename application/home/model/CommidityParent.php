<?php
namespace app\home\model;
use think\Model;
use think\Db;

class CommidityParent extends Model
{
	const TB_COMMIDITY_PARENT = 'commidity_parent';
	protected $pk = 'id';

	//获取商品类别名称
	public function getCommidityParentName()
	{
		$fields = [
			self::TB_COMMIDITY_PARENT . '.id as id',
			self::TB_COMMIDITY_PARENT . '.name as name',
		];

		$result = Db::table(self::TB_COMMIDITY_PARENT)
			-> field($fields)
			-> select();

		return $result = $result ? $result : '';
	}
}