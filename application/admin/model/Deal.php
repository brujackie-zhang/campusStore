<?php
namespace app\admin\model;
use think\Model;
use think\Db;

class Deal extends Model
{
	const TB_DEAL             = 'deal';
	const TB_COMMIDITY        = 'commidity';
	const TB_COMMIDITY_TYPE   = 'commidity_type';
	const TB_COMMIDITY_PARENT = 'commidity_parent';
	const TB_SHOPS            = 'shops';
	const TB_USER             = 'user';

	//获取订单信息
	public function getDealInfo($perPage = 10)
	{
		$fields = [
			self::TB_DEAL . '.id as id',
			self::TB_DEAL . '.deal_number as dealNumber',
			self::TB_DEAL . '.commidity_id as commidityId',
			self::TB_COMMIDITY . '.name as commidityName',
			self::TB_COMMIDITY . '.image as commidityImage',
			self::TB_USER . '.name as userName',
			self::TB_DEAL . '.commidity_num as commidityNum',
			self::TB_DEAL . '.total as total',
			self::TB_DEAL . '.pay_method as payMethod',
			self::TB_DEAL . '.delivery_method as deliveryMethod',
			"FROM_UNIXTIME(" . self::TB_DEAL . '.create_time, "%Y-%m-%d %H:%i:%s") as createTime',
			"FROM_UNIXTIME(" . self::TB_DEAL . '.finish_time, "%Y-%m-%d %H:%i:%s") as finishTime',
			self::TB_DEAL . '.status as status',
		];
		$total = $this -> getDealInfoTotal();

		$result = Db::table(self::TB_DEAL)
			-> field($fields)
			-> join(self::TB_COMMIDITY, self::TB_COMMIDITY . '.id = ' . self::TB_DEAL . '.commidity_id', 'left')
			-> join(self::TB_USER, self::TB_USER . '.id = ' . self::TB_DEAL . '.user_id', 'left')
			-> order(self::TB_DEAL . '.id', 'asc')
			-> paginate($perPage, $total);

		return $result = $result ? $result : "";
	}

	//获取订单信息总数
	public function getDealInfoTotal()
	{
		$fields = [
			self::TB_DEAL . '.id as id',
			self::TB_DEAL . '.deal_number as dealNumber',
			self::TB_DEAL . '.commidity_id as commidityId',
			self::TB_COMMIDITY . '.name as commidityName',
			self::TB_COMMIDITY . '.image as commidityImage',
			self::TB_USER . '.name as userName',
			self::TB_DEAL . '.commidity_num as commidityNum',
			self::TB_DEAL . '.total as total',
			self::TB_DEAL . '.pay_method as payMethod',
			self::TB_DEAL . '.delivery_method as deliveryMethod',
			"FROM_UNIXTIME(" . self::TB_DEAL . '.create_time, "%Y-%m-%d %H:%i:%s") as createTime',
			"FROM_UNIXTIME(" . self::TB_DEAL . '.finish_time, "%Y-%m-%d %H:%i:%s") as finishTime',
			self::TB_DEAL . '.status as status',
		];

		$result = Db::table(self::TB_DEAL)
			-> field($fields)
			-> join(self::TB_COMMIDITY, self::TB_COMMIDITY . '.id = ' . self::TB_DEAL . '.commidity_id', 'left')
			-> join(self::TB_USER, self::TB_USER . '.id = ' . self::TB_DEAL . '.user_id', 'left')
			-> order(self::TB_DEAL . '.id', 'asc')
			-> count();

		return $result = $result ? $result : "";
	}

	//根据查询条件查询订单
	public function searchDeal($condition = '')
	{
		$fields = [
			self::TB_DEAL . '.id as id',
			self::TB_DEAL . '.deal_number as dealNumber',
			self::TB_DEAL . '.commidity_id as commidityId',
			self::TB_COMMIDITY . '.name as commidityName',
			self::TB_COMMIDITY . '.image as commidityImage',
			self::TB_USER . '.name as userName',
			self::TB_DEAL . '.commidity_num as commidityNum',
			self::TB_DEAL . '.total as total',
			self::TB_DEAL . '.pay_method as payMethod',
			self::TB_DEAL . '.delivery_method as deliveryMethod',
			"FROM_UNIXTIME(" . self::TB_DEAL . '.create_time, "%Y-%m-%d %H:%i:%s") as createTime',
			"FROM_UNIXTIME(" . self::TB_DEAL . '.finish_time, "%Y-%m-%d %H:%i:%s") as finishTime',
			self::TB_DEAL . '.status as status',
		];

		if (isset($condition) && !empty($condition)) {
			Db::table(self::TB_DEAL) -> where(self::TB_DEAL . '.id', 'like', '%' . $condition . '%')
				-> whereOr(self::TB_DEAL . '.deal_number', 'like', '%' . $condition . '%')
				-> whereOr(self::TB_USER . '.name', 'like', '%' . $condition . '%')
				-> whereOr(self::TB_COMMIDITY . '.name', 'like', '%' . $condition . '%')
				-> whereOr(self::TB_DEAL . '.pay_method', 'like', '%' . $condition . '%');
		}

		$result = Db::table(self::TB_DEAL)
			-> field($fields)
			-> join(self::TB_COMMIDITY, self::TB_COMMIDITY . '.id = ' . self::TB_DEAL . '.commidity_id', 'left')
			-> join(self::TB_USER, self::TB_USER . '.id = ' . self::TB_DEAL . '.user_id', 'left')
			-> order(self::TB_DEAL . '.id', 'asc')
			-> select();

		return $result = $result ? $result : "";
	}

	//根据商品父类别获取各类别订单总数
	public function getCommidityParentDealTotal()
	{
		$fields = [
			'COUNT(*) as total',
			self::TB_COMMIDITY_PARENT . '.name as name',
		];

		$result = Db::table(self::TB_DEAL)
			-> field($fields)
			-> join(self::TB_COMMIDITY, self::TB_DEAL . '.commidity_id = ' . self::TB_COMMIDITY . '.id', 'left')
			-> join(self::TB_COMMIDITY_TYPE, self::TB_COMMIDITY . '.commidity_type_id = ' . self::TB_COMMIDITY_TYPE . '.id', 'left')
			-> join(self::TB_COMMIDITY_PARENT, self::TB_COMMIDITY_TYPE . '.p_id = ' . self::TB_COMMIDITY_PARENT . '.id', 'left')
			-> group(self::TB_COMMIDITY_PARENT . '.id')
			-> select();

		return $result = $result ? $result : "";
	}

	//获取订单状态情况（成功或取消）
	public function getDealStatus()
	{
		$fields = [
			self::TB_DEAL . '.status as status',
			'COUNT(*) as count',
		];

		$result = Db::table(self::TB_DEAL)
			-> field($fields)
			-> group(self::TB_DEAL . '.status')
			-> select();

		return $result = $result ? $result : "";
	}

	//根据商品类别获取各类别订单总数
	public function getCommidityTypeDealTotal()
	{
		$fields = [
			'COUNT(*) as total',
			self::TB_COMMIDITY_TYPE . '.name as name',
		];

		$result = Db::table(self::TB_DEAL)
			-> field($fields)
			-> join(self::TB_COMMIDITY, self::TB_DEAL . '.commidity_id = ' . self::TB_COMMIDITY . '.id', 'left')
			-> join(self::TB_COMMIDITY_TYPE, self::TB_COMMIDITY . '.commidity_type_id = ' . self::TB_COMMIDITY_TYPE . '.id', 'left')
			-> group(self::TB_COMMIDITY_TYPE . '.id')
			-> select();

		return $result = $result ? $result : "";
	}
}