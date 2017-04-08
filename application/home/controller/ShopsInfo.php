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

		$shops = new Shops;
		$total = $shops -> getShopsInfoTotal();
		$result = $shops -> getShopsInfo(2, $total);

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
}