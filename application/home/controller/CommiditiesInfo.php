<?php
namespace app\home\controller;
use think\Controller;
use think\Session;
use think\Request;
use app\home\model\Comment;
use app\home\model\Commidity;
use app\home\model\Deal;

error_reporting(0);

class CommiditiesInfo extends Controller
{
	//根据商品ID获取商品详细信息
	public function getCommidityInfoByCommidityId($commidityId)
	// public function getCommidityInfoByCommidityId()
	{
		$login = new Login();
		$name = $login -> getUserSessionInfo()['name'];
		$userId = $login -> getUserSessionInfo()['id'];
		$userImage = $login -> getUserSessionInfo()['image'];
		//实例化商品
		$commidity = new Commidity;
		$result = $commidity ->getCommidityInfoByCommidityId($commidityId);
	// print_r(json_decode($result[0]['extraInfo'], true));
		// $result[0]['discount'] = $result[0]['discount'] * 100;

		//获取商品额外信息
		$extraInfo               = json_decode($result[0]['extraInfo'], true);
		$extraInfoForCommidities = $extraInfo ?? '';
		$extraInfoForStyle       = $extraInfoForCommidities['style'] ?? '';
		$extraInfoForTechnology  = $extraInfoForCommidities['technology'] ?? '';
		$extraInfoForTechnology1 = $extraInfoForCommidities['technology1'] ?? '';
		$extraInfoForUrgency     = $extraInfoForCommidities['urgency'] ?? '';
		$extraInfoForBackground  = $extraInfoForCommidities['background'] ?? '';
		$extraInfoForFont        = $extraInfoForCommidities['font'] ?? '';

		//获取买过该商品的人
		$userBuyCommidity = $commidity -> getUserIdByCommidityId($commidityId);
		if ($userBuyCommidity) {
			foreach ($userBuyCommidity as $v) {
				$userIds[] = $v['userId'];
			}
			//获取买过该商品的人还买过的商品信息
			$commidityInfoFromUser = $commidity -> getCommiditiesInfoFromDeal($userIds, $commidityId);

			//实例化搜索
			$search = new Search();
			$parent = $search -> getCommidityParentName();
			$this -> assign('parent', $parent);

			//实例化评论
			$comment = new Comment;
			$commentTotal = $comment -> getCommentTotal($commidityId, []);
			$commentTotal = $commentTotal ? $commentTotal : 0;
			//获取商品对应的详细图片
			$pictures = json_decode($result[0]['picture'], true);
			if ($result) {
				session('commidityInfo', $result[0]);
				$this -> assign(
					[
						'name'                    => $name,
						'userImage'               => $userImage,
						'commiditiesInfo'         => $result[0],
						'commentTotal'            => $commentTotal,
						'tommorrow'               => date('m月d日', time() + 24 * 3600),
						'commidityId'             => $commidityId,
						'commidityInfoFromUser'   => $commidityInfoFromUser,
						'pictures'                => $pictures,
						'extraInfoForStyle'       => $extraInfoForStyle,
						'extraInfoForTechnology'  => $extraInfoForTechnology,
						'extraInfoForTechnology1' => $extraInfoForTechnology1,
						'extraInfoForUrgency'     => $extraInfoForUrgency,
						'extraInfoForBackground'  => $extraInfoForBackground,
						'extraInfoForFont'        => $extraInfoForBackground,
					]
				);
				if ($result[0]['cParentName'] == '打印业务') {
					return $this -> fetch('commiditiesInfoByPrint');
				} else {
					return $this -> fetch('commiditiesInfo');
				}
			} else {
				return $this -> error('暂时没有数据！o(╯□╰)o');
			}
		} else {
			//实例化商品
			$commidity = new Commidity();
			$commidityInfoFromUser = $commidity -> getCommidityInfoByCommidityId($commidityId);
			//实例化评论
			$comment = new Comment;
			$commentTotal = $comment -> getCommentTotal($commidityId, []);
			$commentTotal = $commentTotal ? $commentTotal : 0;

			//实例化搜索
			$search = new Search();
			$parent = $search -> getCommidityParentName();

			//实例化评论
			$comment = new Comment;
			$commentTotal = $comment -> getCommentTotal($commidityId, []);
			$commentTotal = $commentTotal ? $commentTotal : 0;

			//获取商品对应的详细图片
			$pictures = json_decode($result[0]['picture'], true);
			if ($result) {
				session('commidityInfo', $result);
				$this -> assign(
					[
						'name'                    => $name,
						'userImage'               => $userImage,
						'commiditiesInfo'         => $result[0],
						'commentTotal'            => $commentTotal,
						'tommorrow'               => date('m月d日', time() + 24 * 3600),
						'commidityId'             => $commidityId,
						'commidityInfoFromUser'   => $commidityInfoFromUser,
						'pictures'                => $pictures,
						'extraInfoForStyle'       => $extraInfoForStyle,
						'extraInfoForTechnology'  => $extraInfoForTechnology,
						'extraInfoForTechnology1' => $extraInfoForTechnology1,
						'extraInfoForUrgency'     => $extraInfoForUrgency,
						'extraInfoForBackground'  => $extraInfoForBackground,
						'extraInfoForFont'        => $extraInfoForBackground,
					]
				);
				if ($result[0]['cParentName'] == '打印业务') {
					return $this -> fetch('commiditiesInfoByPrint');
				} else {
					return $this -> fetch('commiditiesInfo');
				}
			} else {
				return $this -> error('暂时没有数据！o(╯□╰)o');
			}
		}	
	}

	//根据商品父类ID获取商品信息
	public function getCommiditiesByParentId()
	{
		$login = new Login();
		$name = $login -> getUserSessionInfo()['name'];
		$userImage = $login -> getUserSessionInfo()['image'];

		$pId = input('post.selectSearch');
		$condition = input('post.searchContent');
		//实例化搜索
		$search = new Search();
		$parent = $search -> getCommidityParentName();
		
		//实例化商品
		$commidity = new Commidity;
		$total = $commidity -> getCommiditiesByParentIdTotal($pId, $condition);
		$result = $commidity -> getCommiditiesByParentId($pId, 100, $total, $condition);
		// echo $commidity -> getLastSql();
		if ($result) {
			$page = $result -> render();
			$this -> assign(
				[
					'name'        => $name,
					'userImage'   => $userImage,
					'parent'      => $parent,
					'commidities' => $result,
					'page'        => $page,
				]
			);
			return $this -> fetch('searchCommidities');
		} else {
			return $this -> error('暂时没有数据！o(╯□╰)o');
		}
	}

	//获取用户对应的订单
	public function getDealInfoByUserId()
	{
		$login = new Login();
		$name = $login -> getUserSessionInfo()['name'];
		$userId = $login -> getUserSessionInfo()['id'];
		$userImage = $login -> getUserSessionInfo()['image'];
		//实例化搜索
		$search = new Search();
		$parent = $search -> getCommidityParentName();
		$this -> assign('parent', $parent);

		$deal = new Deal;
		$total = $deal -> getDealInfoByUserIdTotal($userId);
		$result = $deal -> getDealInfoByUserId($userId, 10, $total);

		$status = [
			'0' => '待付款',
			'1' => '成功',
			'2' => '已取消',
		];
		if ($result) {
			$page = $result -> render();
			$this -> assign(
				[
					'list'      => $result,
					'status'    => $status,
					'name'      => $name,
					'page'      => $page,
					'userImage' => $userImage,
				]
			);
			return $this -> fetch('commidities_info/carByUser');
		}
	}

	//购物车结算（非打印业务）
	public function carAccount()
	{
		if (Request::instance() -> isPost()) {
			$login = new Login();
			$name = $login -> getUserSessionInfo()['name'];
			$userId = $login -> getUserSessionInfo()['id'];
			$userImage = $login -> getUserSessionInfo()['image'];

			$commidityId  = input('post.commidity_id');
			$commidityNum = input('post.commidity_num');
			$price        = input('post.price');
			$discount     = input('post.discount');
			$total        = input('post.total');
			$delivery     = input('post.delivery_method');

	    	$data = [
				'deal_number'     => date('YmdHis', time()) . $commidityId,
				'commidity_id'    => $commidityId,
				'user_id'         => $userId,
				'commidity_num'   => $commidityNum,
				'price'           => $price,
				'discount'        => $discount,
				'delivery_method' => $delivery,
				'total'           => $total,
				'create_time'     => time(),
	    	];

	    	$deal = new Deal;
	    	$result = $deal -> generateDeal($data);

	    	//实例化搜索
			$search = new Search();
			$parent = $search -> getCommidityParentName();
			$this -> assign(
	    		[
					'name'      => $name,
					'parent'    => $parent,
					'userImage' => $userImage,
	    		]
	    	);
			
			// if ($result) {
			// 	return $this -> success('订单添加成功！');
			// } else {
			// 	return $this -> error('订单添加失败！');
			// }
			if (! $result) {
				return $this -> error('订单添加失败！');
			}
		} else {
			$login = new Login();
			$name = $login -> getUserSessionInfo()['name'];
			$userId = $login -> getUserSessionInfo()['id'];
			$userImage = $login -> getUserSessionInfo()['image'];
			//实例化搜索
			$search = new Search();
			$parent = $search -> getCommidityParentName();
			$this -> assign(
	    		[
					'name'      => $name,
					'parent'    => $parent,
					'userImage' => $userImage,
	    		]
	    	);
			return $this -> goToAccount();
		}
	}

	//购物车结算（打印业务）
	public function carAccountForPrint()
	{
		$login = new Login();
		$name = $login -> getUserSessionInfo()['name'];
		$userId = $login -> getUserSessionInfo()['id'];
		$userImage = $login -> getUserSessionInfo()['image'];

		$commidityId  = input('post.commidity_id');
		$commidityNum = input('post.commidity_num');
		$standard     = input('post.standard');
		$technology   = input('post.technology');
		$urgency      = input('post.urgency');
		$other        = input('post.other');
		$files        = input('post.files');
		$price        = input('post.price');
		$discount     = input('post.discount');
		$total        = input('post.total');
		$delivery     = input('post.delivery_method');
	
    	$data = [
			'deal_number'     => date('YmdHis', time()) . $commidityId,
			'commidity_id'    => $commidityId,
			'user_id'         => $userId,
			'commidity_num'   => $commidityNum,
			'standard'        => $standard,
			'technology'      => $technology,
			'urgency'         => $urgency,
			'other'           => $other,
			'files'			  => $files,
			'price'           => $price,
			'discount'        => $discount,
			'delivery_method' => $delivery,
			'total'           => $total,
			'create_time'     => time(),
    	];

    	$deal = new Deal;
    	$result = $deal -> generateDeal($data);

    	//实例化搜索
		$search = new Search();
		$parent = $search -> getCommidityParentName();
		$this -> assign(
    		[
				'name'      => $name,
				'parent'    => $parent,
				'userImage' => $userImage,
    		]
    	);
		
		if ($result) {
			return $this -> success('订单添加成功！');
		} else {
			return $this -> error('订单添加失败！');
		}
			
	}

	//购物车订单
	public function goToAccount()
	{
		$login = new Login();
		$name = $login -> getUserSessionInfo()['name'];
		$userId = $login -> getUserSessionInfo()['id'];
		$userImage = $login -> getUserSessionInfo()['image'];

		$search = new Search();
		$parent = $search -> getCommidityParentName();
		//实例化订单
		$deal = new Deal;
		$result = $deal -> getNewDealsByUserId($userId);
		// echo $deal -> getLastSql();
		// print_r($result);	
		if ($result) {
			$totalPrice = 0;
			$discountPrice = 0;
			foreach ($result as $v) {
				$totalPrice += $v['total'];
				$discountPrice += $v['commidityPrice'] * $v['commidityNum'] * (100 - $v['discount']) / 100.0;
			}
			$this -> assign(
	    		[
					'name'          => $name,
					'userImage'     => $userImage,
					'parent'        => $parent,
					'list'          => $result,
					'totalPrice'    => $totalPrice,
					'discountPrice' => $discountPrice,
	    		]
    		);
    		return $this -> fetch('car');
		} else {
			return $this -> error('暂时没有数据！o(╯□╰)o', url('login/login'));
		}
	}

	//根据订单Id删除订单信息
	public function deleteDealById($id)
	{
		$deal = new Deal;
		$res = $deal -> deleteDealById($id);

		$login = new Login();
		$name = $login -> getUserSessionInfo()['name'];
		//实例化搜索
		$search = new Search();
		$parent = $search -> getCommidityParentName();
		$this -> assign('parent', $parent);

		if ($res) {
			$this -> success('删除订单成功！');
			// $this -> assign('name', $name);
			// return $this -> fetch('car');
		} else {
			return $this -> error('删除订单失败！'.$res);
		}
	}

	//根据订单Id修改订单信息
	public function modifyDealById($id)
	{
		$login = new Login();
		$name = $login -> getUserSessionInfo()['name'];

		$commidityNum     = input('post.commidity_num');
		$discount         = input('post.discount');
		$total            = input('post.total');
		
    	$data = [
			'commidity_num'   => $commidityNum,
			'discount'        => $discount,
			'total'           => $total,
			'create_time'     => time(),
    	];

    	$deal = new Deal;
		$res = $deal -> modifyDealById($id, $data);

		//实例化搜索
		$search = new Search();
		$parent = $search -> getCommidityParentName();
		$this -> assign('parent', $parent);

		if ($res) {
			$this -> success('修改订单成功！');
			// $this -> assign('name', $name);
			// return $this -> fetch('car');
		} else {
			return $this -> error('修改订单失败！'.$res);
		}		
	}

	//订单支付页面
	public function pay()
	{
		//获取选择的订单
		$dealNumbers = json_decode(input('post.dealNumbers'), true);
		//获取支付类型
		$payType = input('post.payType');
		$payMethod = [
			'wxpay'  => '微信',
			'alipay' => '支付宝'
		];

		$data = [
			'status'      => 1,
			'finish_time' => time(),
			'pay_method'  => $payMethod[$payType],
		];

		$deal = new Deal;
		$result = $deal -> updateDealsStatus($dealNumbers, $data);
		if ($result) {
			$dealNumbers = implode(',', $dealNumbers);
			return $this -> success('订单 ' . $dealNumbers . ' 支付成功！');
		} else {
			return $this -> error("订单支付失败！" . $deal -> getLastSql());
		}
	}

	//微信支付宝支付接口
	public function wxAliPay()
	{
		$dealNumbers = json_decode(input('post.dealNumbers'), true);

		if (empty($dealNumbers)) {
			return $this -> error("请选择要付款的订单！！");
		} else {
			$deal = new Deal;
			$total = 0;
			$result = $deal -> getDealTotalByDealNumber($dealNumbers);
			foreach ($result as $v) {
				$total += $v['total']; 
			}

			$data = [
				"total"       => $total . "元",
				"payTime"     => date("Y-m-d H:i:s", time()),
				"payNumber"   => date("YmdHis", time()) . $result[0]["dealNumbers"],
				"payDescribe" => "支付" . implode(",", $dealNumbers) . "订单",
				"dealNumbers" => input('post.dealNumbers'),
	    	];
			return $data;
		}
	}

	//微信支付宝订单详情
	public function wxAliPayInfo()
	{
		$login = new Login();
		$name = $login -> getUserSessionInfo()['name'];
		$userId = $login -> getUserSessionInfo()['id'];
		$userImage = $login -> getUserSessionInfo()['image'];
		$search = new Search();
		$parent = $search -> getCommidityParentName();

		$this -> assign(
    		[
				'name'        => $name,
				'userImage'   => $userImage,
				'parent'      => $parent,
    		]
		);
		return $this -> fetch('wxalipay');
	}
}