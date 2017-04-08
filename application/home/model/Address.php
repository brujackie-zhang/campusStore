<?php
namespace app\home\model;
use think\Model;
use think\Db;

class Address extends Model
{
	const TB_ADDRESS = 'address';
	const TB_USER    = 'user';

	public function __construct()
	{
		parent::__construct();
	}

	//获取用户地址信息
	public function getAddressByUserId($userId)
	{
		$fields = [
			self::TB_ADDRESS . '.id as id',
			self::TB_ADDRESS . '.user_id as userId',
			self::TB_ADDRESS . '.address_info as addressInfo',
			self::TB_ADDRESS . '.receiver as receiver',
			self::TB_ADDRESS . '.mobile as mobile',
		];
		$result = Db::table(self::TB_ADDRESS)
			-> field($fields)
			-> join(self::TB_USER, self::TB_USER . '.id = ' . self::TB_ADDRESS . '.user_id')
			-> where(self::TB_ADDRESS . '.user_id', $userId)
			-> select();

		return $result = $result ? $result : '';
	}

	//根据地址Id 删除地址
	public function deleteAddressById($id)
	{
		return Db::table(self::TB_ADDRESS) -> where(self::TB_ADDRESS . '.id', $id) -> delete();
	}
}