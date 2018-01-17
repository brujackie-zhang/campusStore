<?php
namespace app\home\controller;
use think\Controller;
use app\home\model\Shops;
use app\home\model\Comment;

class ShopsInfo extends Controller
{
	//展示所有店铺信息
	public function displayShops()
	{
		$login = new Login();
		$name = $login -> getUserSessionInfo()['name'];
		$userId = $login -> getUserSessionInfo()['id'];
		$userImage = $login -> getUserSessionInfo()['image'];
		$search = new Search();
		$parent = $search -> getCommidityParentName();
 		
 		//实例化店铺
		$shops = new Shops;
		$total = $shops -> getShopsInfoTotal();
		$result = $shops -> getShopsInfo(10, $total);

		if($result) {
			$page = $result -> render();
			$this -> assign(
	    		[
					'name'      => $name,
					'parent'    => $parent,
					'userImage' => $userImage,
					'page'      => $page,
					'shopsInfo' => $result,
	    		]
	    	);
			return $this -> fetch("shops/shops");
		} else {
			return $this -> error("暂时没有数据！o(╯□╰)o");
		}
	}

	//展示单个店铺详细信息
	public function displayShopDetailInfo($id)
	{
		$login = new Login();
		$name = $login -> getUserSessionInfo()['name'];
		$userId = $login -> getUserSessionInfo()['id'];
		$userImage = $login -> getUserSessionInfo()['image'];
		$search = new Search();
		$parent = $search -> getCommidityParentName();

		//店铺下商品总数
		$shops = new Shops;
		$total = $shops -> getCommiditiesInfoByShopIdTotal($id);
		$results = $shops -> getCommiditiesInfoByShopId($id, 4, $total);
		// echo $shops ->  getLastSql();
		//获取店铺信息
		$shopInfo = $shops -> getShopInfoByShopId($id);
		//获取商品评价
		$comment = new Comment;

		if ($results) {
			foreach ($results as $result) {
				$commidityId = $result['commidityId'];
				$commentTotal[$commidityId] = $comment -> getCommentTotal($commidityId, []);
				$commentTotal[$commidityId] = $commentTotal[$commidityId] ? $commentTotal[$commidityId] : '0';
			}
			//分页
			$page = $results -> render();
			$this -> assign(
	    		[
					'name'                => $name,
					'parent'              => $parent,
					'userImage'           => $userImage,
					'shopCommiditiesInfo' => $results,
					'shopInfo'            => $shopInfo,
					'commentTotal'        => $commentTotal,
					'page'                => $page,  		
				]
	    	);
	    	return $this -> fetch('shops/shopDetailInfo');
		}
	}

	//根据搜索条件查询店铺
	public function getShopSInfoByCondition()
	{
		$condition = input('post.condition');
		// $condition = input('post.select_shops');

		$login = new Login();
		$name = $login -> getUserSessionInfo()['name'];
		$userId = $login -> getUserSessionInfo()['id'];
		$userImage = $login -> getUserSessionInfo()['image'];
		$search = new Search();
		$parent = $search -> getCommidityParentName();
		$shops = new Shops;

		$result = '';

		// $status = [
		// 	'1' => [0, 1],
		// 	'2' => 0,
		// 	'3' => 1, 
		// 	'4' => 5,
		// ];
		$type = [
			'2' => '0', //打印类
			'3' => '1', //非打印类
			'4' => '4.5', //好评
		];

		if ($condition == 2 || $condition == 3) {
			$total = $shops -> getShopSInfoByConditionTotal($type[$condition]);
			$result = $shops -> getShopSInfoByCondition($type[$condition], 10, $total);
		}

		if ($condition == 1) {
			$total = $shops -> getShopsInfoTotal();
			$result = $shops -> getShopsInfo(10, $total);
		}

		if ($condition == 4) {
			$total = $shops -> getShopSInfoByConditionTotal($type[$condition]);
			$result = $shops -> getShopSInfoByCondition($type[$condition], 10, $total);
		}

		if($result) {
			$page = $result -> render();
			// $this -> assign(
	  //   		[
			// 		'name'      => $name,
			// 		'parent'    => $parent,
			// 		'userImage' => $userImage,
			// 		'shopsInfo' => $result,
			// 		'page'      => $page,
	  //   		]
	  //   	);
			// return $this -> fetch("shops/shops");
			// $this -> success("success" . $result);
			return $result;
		} else {
			return $this -> error("暂时没有数据！o(╯□╰)o");
		}
	}
}