<?php
namespace app\home\model;
use think\Model;
use think\Db;

class Deal extends Model
{
	const TB_DEAL           = 'deal';
	const TB_COMMIDITY      = 'commidity';

	//生成订单
	public function generateDeal($data)
	{
		return Db::table(self::TB_DEAL) -> insert($data);
	}

	//根据用户ID 获取订单信息
	public function getDealInfoByUserId($userId, $perPage = 10, $total)
	{
		$fields = [
			self::TB_DEAL . '.id as id',
			self::TB_DEAL . '.deal_number as dealNumber',
			self::TB_DEAL . '.commidity_id as commidityId',
			self::TB_COMMIDITY . '.name as commidityName',
			self::TB_COMMIDITY . '.image as commidityImage',
			self::TB_DEAL . '.commidity_num as commidityNum',
			self::TB_DEAL . '.total as total',
			self::TB_DEAL . '.pay_method as payMethod',
			self::TB_DEAL . '.delivery_method as deliveryMethod',
			"FROM_UNIXTIME(" . self::TB_DEAL . '.create_time, "%Y-%m-%d %H:%i:%s") as time',
			self::TB_DEAL . '.status as status',
		];

		$result = Db::table(self::TB_DEAL)
			-> field($fields)
			-> join(self::TB_COMMIDITY, self::TB_COMMIDITY . '.id = ' . self::TB_DEAL . '.commidity_id')
			-> where(self::TB_DEAL . '.user_id', $userId)
			-> order(self::TB_DEAL . '.id', 'desc')
			-> paginate($perPage, $total);

		return $result = $result ? $result : '';
	}

	//根据用户ID 获取订单总数
	public function getDealInfoByUserIdTotal($userId)
	{
		$fields = [
			self::TB_DEAL . '.id as id',
			self::TB_DEAL . '.deal_number as dealNumber',
			self::TB_COMMIDITY . '.name as commidityName',
			self::TB_COMMIDITY . '.image as commidityImage',
			self::TB_DEAL . '.commidity_num as commidityNum',
			self::TB_DEAL . '.total as total',
			self::TB_DEAL . '.pay_method as payMethod',
			self::TB_DEAL . '.delivery_method as deliveryMethod',
			"FROM_UNIXTIME(" . self::TB_DEAL . '.create_time, "%Y-%m-%d %H:%i:%s") as time',
		];

		$result = Db::table(self::TB_DEAL)
			-> field($fields)
			-> join(self::TB_COMMIDITY, self::TB_COMMIDITY . '.id = ' . self::TB_DEAL . '.commidity_id')
			-> where(self::TB_DEAL . '.user_id', $userId)
			-> order(self::TB_DEAL . '.id', 'desc')
			-> count();

		return $result = $result ? $result : '';
	}

	//根据用户ID 获取新加入的待付款的订单信息
	public function getNewDealsByUserId($userId)
	{
		$fields = [
			self::TB_DEAL . '.id as dealId',
			self::TB_COMMIDITY . '.id as commidityId',
			self::TB_DEAL . '.deal_number as dealNumber',
			self::TB_COMMIDITY . '.name as commidityName',
			self::TB_COMMIDITY . '.price as commidityPrice',
			self::TB_COMMIDITY . '.image as commidityImage',
			self::TB_DEAL . '.commidity_num as commidityNum',
			self::TB_DEAL . '.total as total',
			self::TB_DEAL . '.discount as discount',
			self::TB_DEAL . '.pay_method as payMethod',
			self::TB_DEAL . '.delivery_method as deliveryMethod',
			"FROM_UNIXTIME(" . self::TB_DEAL . '.create_time, "%Y-%m-%d %H:%i:%s") as time',
		];

		$result = Db::table(self::TB_DEAL)
			-> field($fields)
			-> join(self::TB_COMMIDITY, self::TB_COMMIDITY . '.id = ' . self::TB_DEAL . '.commidity_id')
			-> where(self::TB_DEAL . '.user_id', $userId)
			-> where(self::TB_DEAL . '.status', 0)
			-> order('dealId', 'desc')
			-> select();

		return $result = $result ? $result : '';
	}

	//根据订单ID删除订单信息
	public function deleteDealById($id)
	{
		return Db::table(self::TB_DEAL) -> where(self::TB_DEAL . '.id', $id) -> delete();
	}

	//根据订单ID修改订单信息
	public function modifyDealById($id, $data)
	{
		return Db::table(self::TB_DEAL) -> where(self::TB_DEAL . '.id', $id) -> update($data);
	}

	//付款批量更新订单状态
	public function updateDealsStatus($dealNumber, $data)
	{
		// foreach ($dealNumbers as $dealNumber) {
		// 	Db::table(self::TB_DEAL) -> where(self::TB_DEAL . '.deal_number', $dealNumber) -> update($data);
		// 	return 1;
		// }
		// return 0;

		return Db::table(self::TB_DEAL) -> where(self::TB_DEAL . '.deal_number', $dealNumber) -> update($data);
	}
}