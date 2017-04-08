<?php
namespace app\home\controller;
use think\View;
use think\Controller;
use think\Validate;
use think\Session;
use think\Db;
use think\Request;
use think\Loader;
use app\home\model\User;
use app\home\model\Deal;
use app\home\model\Address;

class AddressInfo extends Controller
{
	//添加用户地址
	public function addAddress()
	{
		if ($this -> getAddress()) {
			if (Request::instance() -> isPost()) {
				$province = input('post.province');
				$city     = input('post.city');
				$district = input('post.district');
				$address  = input('post.address');
				$receiver = input('post.receiver');
				$mobile   = input('post.mobile');
				$id       = Session::get('userInfo.id');

				$validate = Loader::validate('Address');
				$data = [
					'province' => $province,
					'city'     => $city,
					'address'  => $address,
					'receiver' => $receiver,
					'mobile'   => $mobile,
				];

				// if (! $validate -> scene('add') -> check($data)) {
				if (! $validate -> check($data)) {
					return $this -> error($validate -> getError());
				} else {
					$address = $province . $city . $district . $address;
					$value = [
						'user_id'      => $id,
						'address_info' => $address,
						'receiver'     => $receiver,
						'mobile'       => $mobile,
					];
					$res = Db::table('address') -> insert($value);
					if ($res) {
						return $this -> success('地址添加成功！');
					} else {
						return $this -> error('地址添加失败！');
					}
				}
			} else {
				return $this -> fetch('address_info/address');
			}
		}
	}

	//获得用户已有地址
	public function getAddress()
	{
		$userId = Session::get('userInfo.id');

		//实例化地址
		$address = new Address();
		$result = $address -> getAddressByUserId($userId);
		$login = new Login;
		$name = $login -> getUserSessionInfo()['name'];
		$image = $login -> getUserSessionInfo()['image'];
		//实例化搜索
		$search = new Search();
		$parent = $search -> getCommidityParentName();
		$this -> assign(
			[
				'name'      => $name,
				'parent'    => $parent,
				'userImage' => $image,
			]
		);

		if ($result) {
			$this -> assign('addressInfo', $result);
			return $this -> fetch('address_info/address');
		} else {
			$this -> assign('addressInfo', '');
			return $this -> fetch('address_info/address');
		}
	}

	//根据地址Id 删除地址
	public function deleteAddressById($id)
	{
		$address = new Address();
		$result = $address -> deleteAddressById($id);

		if ($result) {
			$this -> success("地址删除成功！");
			// return $this -> fetch('address_info/address');
		} else {
			return $this -> error("地址删除失败！");
		}
	}
}