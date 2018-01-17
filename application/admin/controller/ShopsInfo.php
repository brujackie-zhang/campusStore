<?php
namespace app\admin\controller;
use think\Controller;
use think\Validate;
use think\Loader;
use app\admin\model\Shops;

class ShopsInfo extends Controller
{
	//获取店铺信息
	public function getCommidityParentInfoForLogin()
	{
		$commidityParent = new CommidityParent;
		$result = $commidityParent -> getCommidityParentInfo(7);

		if ($result) {
			return $result;
		} else {
			return $this -> error("暂无数据):");
		}
	}

	//获取店铺信息
	public function getShopsInfo()
	{
		$login = new Login();
		$userName = $login -> getUserSessionInfo()['name'];
		$userId = $login -> getUserSessionInfo()['id'];
		$adminImage = $login -> getUserSessionInfo()['image'];

		//店铺类型
		$type = [
			'0' => '打印类',
			'1' => '非打印类'
		];

		$shops = new Shops;
		$result = $shops -> getShopsInfo(3);

		if ($result) {
			$page = $result -> render();
			$this -> assign(
				[
					'name'       => $userName,
					'adminImage' => $adminImage,
					'shopsInfo'  => $result,
					'type'       => $type,
					'page'       => $page,
				]
			);
			return $this -> fetch("shops");
		} else {
			return $this -> error("暂无数据):");
		}
	}

	//根据ID获取单个店铺信息
	public function getShopsInfoById($id)
	{
		$login = new Login();
		$userName = $login -> getUserSessionInfo()['name'];
		$userId = $login -> getUserSessionInfo()['id'];
		$adminImage = $login -> getUserSessionInfo()['image'];

		//店铺类型
		$type = [
			'0' => '打印类',
			'1' => '非打印类'
		];

		$shops = new Shops;
		$result = $shops -> getShopsInfoById($id);

		if ($result) {
			$this -> assign(
				[
					'name'          => $userName,
					'adminImage'    => $adminImage,
					'shopsInfoById' => $result,
					'type'          => $type,
				]
			);
			return $this -> fetch("modifyShops");
		} else {
			return $this -> error("暂无数据):");
		}
	}

	//根据ID删除店铺
	public function deleteShopsById($id)
	{
		$shops = new Shops;
		$res = $shops -> deleteShopsById($id);

		if ($res) {
			$this -> success("删除店铺成功！");
		} else {
			$this -> error("删除店铺失败！");
		}
	}

	//根据Id修改店铺信息
	public function modifyShopsById($id)
	{
		$shopsName        = input('post.shopsName');
		$shopsSevices     = input('post.shopsSevices');
		$shopsDescription = input('post.shopsDescription');
		$shopsFace        = input('post.shopsFace');
		$shopsLicense     = input('post.shopsLicense');
		$shopsAddress     = input('post.shopsAddress');
		$shopsMobile      = input('post.shopsMobile');
		$shopsqq          = input('post.shopsqq');
		$shopsAlipay      = input('post.shopsAlipay');
		$shopsWxpay       = input('post.shopsWxpay');
		$shopsOnDuty     = input('post.shopsOnDuty');
		$shopsOffDuty    = input('post.shopsOffDuty');
		$shopsType        = input('post.shopsType');
		
    	$data = [
			'name'        => $shopsName,
			'sevices'     => $shopsSevices,
			'description' => $shopsDescription,
			'face'        => $shopsFace,
			'license'     => $shopsLicense,
			'address'     => $shopsAddress,
			'mobile'      => $shopsMobile,
			'qq'          => $shopsqq,
			'alipay'      => $shopsAlipay,
			'wxpay'       => $shopsWxpay,
			'on_duty'     => $shopsOnDuty,
			'off_duty'    => $shopsOffDuty,
			'type'        => $shopsType,
			'create_time' => time(),
		];

    	$shops = new Shops;
		$res = $shops -> updateShopsById($id, $data);

		if ($res) {
			return $this -> success('修改店铺信息成功！');
		} else {
			return $this -> error('修改店铺信息失败！');
		}		
	}

	//添加店铺
	public function addShops()
	{
		$shopsName        = input('post.shopsName');
		$shopsSevices     = input('post.shopsSevices');
		$shopsDescription = input('post.shopsDescription');
		$shopsFace        = input('post.shopsFace');
		$shopsLicense     = input('post.shopsLicense');
		$shopsAddress     = input('post.shopsAddress');
		$shopsMobile      = input('post.shopsMobile');
		$shopsqq          = input('post.shopsqq');
		$shopsAlipay      = input('post.shopsAlipay');
		$shopsWxpay       = input('post.shopsWxpay');
		$shopsOnDuty      = input('post.shopsOnDuty');
		$shopsOffDuty     = input('post.shopsOffDuty');
		$shopsType        = input('post.shopsType');

		$validateData = [
			'name'        => $shopsName,
			'sevices'     => $shopsSevices,
			'description' => $shopsDescription,
			'address'     => $shopsAddress,
			'mobile'      => $shopsMobile,
			'qq'          => $shopsqq,
			'alipay'      => $shopsAlipay,
			'wxpay'       => $shopsWxpay,
			'on_duty'     => $shopsOnDuty,
			'off_duty'    => $shopsOffDuty,
		];

		$validate = Loader::validate('Shops');
		if (! $validate -> check($validateData)) {
			return $this -> error($validate -> getError());
		} else {
			$data = [
				'name'        => $shopsName,
				'sevices'     => $shopsSevices,
				'description' => $shopsDescription,
				'face'        => $shopsFace,
				'license'     => $shopsLicense,
				'address'     => $shopsAddress,
				'mobile'      => $shopsMobile,
				'qq'          => $shopsqq,
				'alipay'      => $shopsAlipay,
				'wxpay'       => $shopsWxpay,
				'on_duty'    => $shopsOnDuty,
				'off_duty'    => $shopsOffDuty,
				'type'        => $shopsType,
				'create_time' => time(),
			];

			$shops = new Shops;
			$res = $shops -> addShops($data);
			if ($res) {
				return $this -> success('添加店铺成功！');
			} else {
				return $this -> error('添加店铺失败！');
			}
		}
	}

	//根据Ids批量删除店铺
	public function deleteShopsByIds()
	{
		//获取选择的店铺
		$shopsIds = json_decode(input('post.shopsIds'), true);
		if (empty($shopsIds)) {
			return $this -> error("请选择要删除的店铺！！！");
		} else {
			$shops = new Shops;
			$result = $shops -> deleteShopsByIds($shopsIds);

			if ($result) {
				$shopsIds = implode(',', $shopsIds);
				return $this -> success('删除 ' . $shopsIds . ' 成功！');
			} else {
				return $this -> error("删除失败！" . $shops -> getLastSql());
			}
		}
	}

	//根据搜索条件查询店铺
	public function searchShops()
	{
		//查询条件
		$condition = input('post.condition');
		if (empty($condition)) {
			return $this -> error("请输入要查询的条件！！");
		} else {
			$shops = new Shops;
			$result = $shops -> searchShops($condition);

			//店铺类型
			$type = [
				'0' => '打印类',
				'1' => '非打印类'
			];
			$data['data'] = $result;
			$data['type'] = $type;
			
			if ($result) {
				return $data;
			} else {
				return $this -> error("暂无数据):");
			}
		}
	}
}