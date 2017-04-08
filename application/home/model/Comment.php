<?php
namespace app\home\model;
use think\Model;
use think\Db;

class Comment extends Model
{
	// $protected $pk = 'id';
	const TB_COMMENT   = 'comment';
	const TB_USER      = 'user';
	const TB_COMMIDITY = 'commidity';

	//构造函数
	public function __construct()
	{
		parent::__construct();
	}

	//获取商品对应的所有评价信息
	public function getCommentByAll($commidityId, $perPage = 10, $total, $condition)
	{
		$fields = [
			self::TB_COMMENT . '.id as id',
			self::TB_USER . '.name as userName',
			self::TB_COMMIDITY . '.id as commidityId',
			self::TB_COMMIDITY . '.name as commidityName',
			self::TB_COMMIDITY . '.image as commidityImage',
			self::TB_COMMENT . '.score as score',
			self::TB_COMMENT . '.content as content',
			'FROM_UNIXTIME(' . self::TB_COMMENT . '.time, "%Y-%m-%d %H:%i:%s") as time',
		];

		if (isset($condition) && ! empty($condition)) {
			Db::table(self::TB_COMMENT) -> where(self::TB_COMMENT . '.score', 'between', $condition);
		}

		$result = Db::table(self::TB_COMMENT)
			-> field($fields)
			-> join(self::TB_USER, self::TB_USER . '.id = ' . self::TB_COMMENT . '.user_id', 'left')
			-> join(self::TB_COMMIDITY, self::TB_COMMIDITY . '.id = ' . self::TB_COMMENT . '.commidity_id', 'left')
			-> where(self::TB_COMMENT . '.commidity_id', $commidityId)
			-> order(self::TB_COMMENT . '.id', 'desc')
			// -> select();
			-> paginate($perPage, $total);

		return $result = $result ? $result : '';
	}

	//获取商品对应的评价总数
	public function getCommentTotal($commidityId, $condition = [])
	{
		$fields = [
			self::TB_COMMENT . '.id as id',
			self::TB_USER . '.name as userName',
			self::TB_COMMIDITY . '.id as commidityId',
			self::TB_COMMIDITY . '.name as commidityName',
			self::TB_COMMIDITY . '.image as commidityImage',
			self::TB_COMMENT . '.score as score',
			self::TB_COMMENT . '.content as content',
			'FROM_UNIXTIME(' . self::TB_COMMENT . '.time, "%Y-%m-%d %H:%i:%s") as time',
		];

		if (isset($condition) && ! empty($condition)) {
			Db::table(self::TB_COMMENT) -> where(self::TB_COMMENT . '.score', 'between', $condition);
		}

		$result = Db::table(self::TB_COMMENT)
			-> field($fields)
			-> join(self::TB_USER, self::TB_USER . '.id = ' . self::TB_COMMENT . '.user_id', 'left')
			-> join(self::TB_COMMIDITY, self::TB_COMMIDITY . '.id = ' . self::TB_COMMENT . '.commidity_id', 'left')
			-> where(self::TB_COMMENT . '.commidity_id', $commidityId)
			-> count();

		return $result = $result ? $result : '';
	}

	//获取用户对应的所有评价信息
	public function getCommentByUser($userId, $perPage = 10, $total, $condition)
	{
		$fields = [
			self::TB_COMMENT . '.id as id',
			self::TB_USER . '.name as userName',
			self::TB_COMMIDITY . '.name as commidityName',
			self::TB_COMMENT . '.score as score',
			self::TB_COMMENT . '.content as content',
			'FROM_UNIXTIME(' . self::TB_COMMENT . '.time, "%Y-%m-%d %H:%i:%s") as time',
		];

		$result = Db::table(self::TB_COMMENT)
			-> field($fields)
			-> join(self::TB_USER, self::TB_USER . '.id = ' . self::TB_COMMENT . '.user_id', 'left')
			-> join(self::TB_COMMIDITY, self::TB_COMMIDITY . '.id = ' . self::TB_COMMENT . '.commidity_id', 'left')
			-> where(self::TB_COMMENT . '.user_id', $userId)
			-> where(self::TB_COMMENT . '.time', 'between', $condition)
			-> order(self::TB_COMMENT . '.id', 'desc')
			-> paginate($perPage, $total);

		return $result = $result ? $result : '';
	}

	//获取用户对应的评价信息总数
	public function getCommentByUserTotal($userId, $condition)
	{
		$fields = [
			self::TB_COMMENT . '.id as id',
			self::TB_USER . '.name as userName',
			self::TB_COMMIDITY . '.name as commidityName',
			self::TB_COMMENT . '.score as score',
			self::TB_COMMENT . '.content as content',
			'FROM_UNIXTIME(' . self::TB_COMMENT . '.time, "%Y-%m-%d %H:%i:%s") as time',
		];

		$result = Db::table(self::TB_COMMENT)
			-> field($fields)
			-> join(self::TB_USER, self::TB_USER . '.id = ' . self::TB_COMMENT . '.user_id', 'left')
			-> join(self::TB_COMMIDITY, self::TB_COMMIDITY . '.id = ' . self::TB_COMMENT . '.commidity_id', 'left')
			-> where(self::TB_COMMENT . '.user_id', $userId)
			-> where(self::TB_COMMENT . '.time', 'between', $condition)
			-> count();

		return $result = $result ? $result : '';
	}

	//根据评价ID获取修改的评价信息
	public function getCommentInfoById($id)
	{
		$fields = [
			self::TB_COMMENT . '.id as commentId',
			self::TB_COMMIDITY . '.name as commidityName',
			self::TB_COMMENT . '.score as score',
			self::TB_COMMENT . '.content as content',
		];

		$result = Db::table(self::TB_COMMENT)
			-> field($fields)
			-> join(self::TB_COMMIDITY, self::TB_COMMIDITY . '.id = ' . self::TB_COMMENT . '.commidity_id', 'left')
			-> where(self::TB_COMMENT . '.id', $id)
			-> find();

		return $result = $result ? $result : '';
	}

	//根据评价ID修改评价信息
	public function modifyCommentById($id, $data)
	{
		return Db::table(self::TB_COMMENT) -> where(self::TB_COMMENT . '.id', $id) -> update($data);
	}

	//根据评价ID删除评价信息
	public function deleteCommentById($id)
	{
		return Db::table(self::TB_COMMENT) -> where(self::TB_COMMENT . '.id', $id) -> delete();
	}

	//根据商品ID生成评价
	public function createCommentByCommidityId($data)
	{
		return Db::table(self::TB_COMMENT) -> insert($data);
	}
}
