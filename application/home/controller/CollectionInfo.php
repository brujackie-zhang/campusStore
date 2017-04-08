<?php
namespace app\home\controller;
use think\Controller;
use think\Request;
use app\home\model\Comment;
use app\home\model\Collection;

class CollectionInfo extends Controller
{
	//获取用户对应的收藏
	public function getMyCollection()
	{
		$login = new Login;
		$id = $login -> getUserSessionInfo()['id'];
		$name = $login -> getUserSessionInfo()['name'];
		$image = $login -> getUserSessionInfo()['image'];
		//实例化搜索
		$search = new Search();
		$parent = $search -> getCommidityParentName();

		$collection = new Collection;
		$result = $collection -> getMyCollection($id);

		if ($result) {
			$this -> assign(
				[
					'list'      => $result,
					'name'      => $name,
					'parent'    => $parent,
					'userImage' => $image,
				]
			);
			return $this -> fetch('collection_info/collection');
		} else {
			return $this -> error('暂时没有数据！o(╯□╰)o', url('login'));
		}
	}

	//根据收藏ID删除收藏信息
	public function deleteCollectionById($id)
	{
		$collection = new Collection();
		$res = $collection -> deleteCollectionById($id);
		$login = new Login;
		$name = $login -> getUserSessionInfo()['name'];
		$image = $login -> getUserSessionInfo()['image'];
		//实例化搜索
		$search = new Search();
		$parent = $search -> getCommidityParentName();

		if ($res) {
			$this -> assign(
				[
					'name'      => $name,
					'parent'    => $parent,
					'userImage' => $image,
				]
			);

			$this -> success('删除收藏成功！');
			return $this -> fetch('collection_info/collection');
		} else {
			return $this -> error('删除收藏失败！');
		}
	}

	//根据商品ID收藏商品
	public function collectionByCommidityId($commidityId)
	{
		$login = new Login();
		$userId = $login -> getUserSessionInfo()['id'];

		$data = [
			'user_id'         => $userId,
			'commidity_id'    => $commidityId,
			'collection_time' => time(),
		];

		$collection = new Collection();
		$res = $collection -> collectionByCommidityId($data);

		if ($res) {
			return $this -> success('收藏商品成功！');
		} else {
			return $this -> error('收藏商品失败！');
		}
	}
}