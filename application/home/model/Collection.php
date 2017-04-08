<?php
namespace app\home\model;
use think\Model;
use think\Db;

class Collection extends Model
{
	const TB_COLLECTION = 'collection';
	const TB_COMMIDITY  = 'commidity';

	//获得用户收藏
	public function getMyCollection($userId)
	{
		$fields = [
			self::TB_COLLECTION . '.id as id',
			self::TB_COMMIDITY . '.name as commidityName',
			self::TB_COMMIDITY . '.image as image',
			self::TB_COMMIDITY . '.price as price',
			"FROM_UNIXTIME(" . self::TB_COLLECTION . '.collection_time, "%Y-%m-%d %H:%i:%s") as collectionTime',
		];

		$result = Db::table(self::TB_COLLECTION)
			-> field($fields)
			-> join(self::TB_COMMIDITY, self::TB_COMMIDITY . '.id = ' . self::TB_COLLECTION . '.commidity_id', 'left')
			-> where(self::TB_COLLECTION . '.user_id', $userId)
			-> order(self::TB_COLLECTION . '.id', 'desc')
			-> select();

		return $result = $result ? $result : '';
	}

	//根据收藏ID删除收藏信息
	public function deleteCollectionById($id)
	{
		return Db::table(self::TB_COLLECTION) -> where(self::TB_COLLECTION . '.id', $id) -> delete();
	}

	//根据商品ID收藏商品
	public function collectionByCommidityId($data)
	{
		return Db::table(self::TB_COLLECTION) -> insert($data);
	}
}