<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Deal;

class DealInfo extends Controller
{
	//获取订单信息
	public function getDealInfo()
	{
		$login = new Login();
		$userName = $login -> getUserSessionInfo()['name'];
		$userId = $login -> getUserSessionInfo()['id'];
		$adminImage = $login -> getUserSessionInfo()['image'];

		$status = [
			'0' => '待付款',
			'1' => '成功',
			'2' => '取消',
		];

		$deal = new Deal;
		$result = $deal -> getDealInfo(9);

		if ($result) {
			$page = $result -> render();
			$this -> assign(
				[
					'name'       => $userName,
					'adminImage' => $adminImage,
					'dealInfo'   => $result,
					'page'       => $page,
					'status'     => $status,
				]
			);
			return $this -> fetch("deal");
		} else {
			return $this -> error("暂无数据):");
		}
	}

	//根据搜索条件查询订单
	public function searchDeal()
	{
		//查询条件
		$condition = input('post.condition');
		if (empty($condition)) {
			return $this -> error("请输入要查询的条件！！");
		} else {
			$deal = new Deal;
			$result = $deal -> searchDeal($condition);
			$status = [
				'0' => '待付款',
				'1' => '成功',
				'2' => '取消',
			];
			$data['data'] = $result;
			$data['status'] = $status;

			if ($result) {
				return $data;
			} else {
				return $this -> error("暂无数据):");
			}
		}
	}

	//统计订单页
	public function totalDealPage()
	{
		$login = new Login();
		$userName = $login -> getUserSessionInfo()['name'];
		$userId = $login -> getUserSessionInfo()['id'];
		$adminImage = $login -> getUserSessionInfo()['image'];

		$this -> assign(
			[
				'name'        => $userName,
				'adminImage'  => $adminImage,
			]
		);
		return $this -> fetch("dealCharts");
	}

	//获取统计订单数据
	public function totalDeal()
	{
		//获取订单总数
		$deal = new Deal;
		$total = $deal -> getDealInfoTotal();
		//获取各父类型订单总数
		$totalParent = $deal -> getCommidityParentDealTotal();
		//获取订单状态
		$dealStatus = $deal -> getDealStatus();
		//获取各类型订单总数
		$totalType = $deal -> getCommidityTypeDealTotal();
		$status = [
			'1' => '成功',
			'0' => '待付款'
		];
		foreach ($dealStatus as $key => $value) {
			$dealStatus[$key]['status'] = $status[$value['status']];
		}

		$result['data']   = $totalParent;
		$result['total']  = $total;
		$result['status'] = $dealStatus;
		$result['type']   = $totalType;

		return json_encode($result);
	}
}