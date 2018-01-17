<?php
namespace app\home\controller;
use think\Controller;
use think\Request;
use app\home\model\Comment;
use app\home\controller\Login;
use app\home\model\Commidity;

class CommentInfo extends Controller
{
	//获取我的评价
	public function getMyComment()
	{
		$login = new Login();
		// print_r($login -> getUserSessionInfo());
		$id = $login -> getUserSessionInfo()['id'];
		$name = $login -> getUserSessionInfo()['name'];
		$image = $login -> getUserSessionInfo()['image'];
		$selectId = input('post.select_comment') ? input('post.select_comment') : '3';
		// $selectId = input('post.select_comment');
		// echo $selectId;
		//实例化搜索
		$search = new Search();
		$parent = $search -> getCommidityParentName();
		$startTime = strtotime(date('Y-m-d 00:00:00', time()));
		$endTime = strtotime(date('Y-m-d 23:59:59', time()));
		$condition = [
			'1' => [$startTime, $endTime],
			'2' => [$startTime - 7 * 86400, $endTime],
			'3' => [$startTime - 31 * 86400, $endTime],
			'4' => [$startTime - 3 * 31 * 86400, $endTime],
			'5' => [$startTime - 12 * 31 * 86400, $endTime],
		];
		$comment = new Comment;
		$total = $comment -> getCommentByUserTotal($id, $condition[$selectId]);
		$result = $comment -> getCommentByUser($id, 5, $total, $condition[$selectId]);
		// $result = $comment -> getCommentByUser($id, $condition[$selectId]);
		// echo $comment -> getLastSql();
		$page = $result -> render();
		if ($result) {
			$this -> assign(
				[
					'comments'  => $result,
					'page'      => $page,
					'name'      => $name,
					'parent'    => $parent,
					'userImage' => $image,
				]
			);
			return $this -> fetch('comment_info/index');
		} else {
			return $this -> error('暂时没有数据！o(╯□╰)o');
		}
	}

	//获取商品所有的评价
	public function getCommidityAllComment($commidityId)
	{
		$login = new Login();
		$name = $login -> getUserSessionInfo()['name'];
		$image = $login -> getUserSessionInfo()['image'];
		//实例化搜索
		$search = new Search();
		$parent = $search -> getCommidityParentName();
		$shop = new \app\home\controller\Shop();
		$selectId = input('post.select_comment') ? input('post.select_comment') : '1';
		$condition = [
			'1' => [4.5, 5.1],
			'2' => [3, 4.5],
			'3' => [0, 3],
		];
		$commidity = $shop -> commidityInfoForIndex();		//实例化商品类并获取商品展示页商品信息
		$comment = new Comment();						//实例化评论类
		$total = $comment -> getCommentTotal($commidityId, $condition[$selectId]); //获取商品对应评价总数
		if ($total == 0) {
			return $this -> error('暂时没有数据！o(╯□╰)o');
		} else {
			$commidityComments = $comment -> getCommentByAll($commidityId, 10, $total, $condition[$selectId]); //获取商品对应所有评价信息带查询条件(好评、中评、差评)
			$commidityCommentsNoCondition = $comment -> getCommentByAll($commidityId, 1, $total, []); //获取商品对应所有评价信息
			if ( ! empty($commidityComments)) {
				$page = $commidityComments -> render();
				$this -> assign(
					[
						'name'              => $name,
						'parent'            => $parent,
						'userImage'         => $image,
						'commidityComments' => $commidityComments,
						'commidityName'     => $commidityCommentsNoCondition[0]['commidityName'],
						'commidityImage'    => $commidityCommentsNoCondition[0]['commidityImage'],
						'commidityId'       => $commidityCommentsNoCondition[0]['commidityId'],
						'page'              => $page,
					]
				);
				return $this -> fetch('comment_info/commentsAll');
			} else {
				return $this -> error('暂时没有数据！o(╯□╰)o');
			}
		}
	}

	//根据评价ID修改评价信息
	public function modifyCommentById($id)
	{
		if (Request::instance() -> isPost()) {
			$commidityName = input('post.commidityName');
			$selectScore = input('post.selectScore');
			$commentContent = input('post.commentContent');
			$commentContent = input('post.commentContent') ? input('post.commentContent') : '好评！☺';
			$data = [
				'score'   => $selectScore,
				'content' => $commentContent,
				'time'    => time(),
			];

			$comment = new Comment();
			$res = $comment -> modifyCommentById($id, $data);

			if ($res) {
				return $this -> success('修改评价成功！');
			} else {
				return $this -> error('修改评价失败！');
			}
		} else {
			$comment = new Comment();
			$result = $comment -> getCommentInfoById($id);
			$login = new Login();
			$name = $login -> getUserSessionInfo()['name'];
			$image = $login -> getUserSessionInfo()['image'];
			$search = new Search();
			$parent = $search -> getCommidityParentName();

			$this -> assign(
				[
					'list'      => $result,
					'name'      => $name,
					'parent'    => $parent,
					'userImage' => $image,
				]
			);

			return $this -> fetch('comment_info/modify');
		}
	}

	//根据商品ID生成评价信息
	public function createCommentByCommidityId($commidityId)
	{
		if (Request::instance() -> isPost()) {
			$login = new Login();
			$userId = $login -> getUserSessionInfo()['id'];

			$commidityName = input('post.commidityName');
			$selectScore = input('post.selectScore');
			$commentContent = input('post.commentContent') ? input('post.commentContent') : '好评！☺';

			$data = [
				'user_id'      => $userId,
				'commidity_id' => $commidityId,
				'score'        => $selectScore,
				'content'      => $commentContent,
				'time'         => time(),
			];

			$comment = new Comment();
			$res = $comment -> createCommentByCommidityId($data);

			if ($res) {
				return $this -> success('评价商品成功！');
			} else {
				return $this -> error('评价商品失败！');
			}
		} else {
			$login = new Login();
			$name = $login -> getUserSessionInfo()['name'];
			$image = $login -> getUserSessionInfo()['image'];
			$search = new Search();
			$parent = $search -> getCommidityParentName();
			$commidity = new Commidity;
			$commidityInfo = $commidity -> getCommidityInfoByCommidityId($commidityId);

			$this -> assign(
				[
					'name'          => $name,
					'parent'        => $parent,
					'userImage'     => $image,
					'commidityName' => $commidityInfo[0]['name'],
					'commidityId'   => $commidityInfo[0]['commidityId'],
				]
			);

			return $this -> fetch('comment_info/create');
		}		
	}

	//根据评价ID删除评价信息
	public function deleteCommentById($id)
	{
		$comment = new Comment();
		$res = $comment -> deleteCommentById($id);

		$login = new Login();
		$name = $login -> getUserSessionInfo()['name'];
		//实例化搜索
		$search = new Search();
		$parent = $search -> getCommidityParentName();
		$this -> assign('parent', $parent);

		if ($res) {
			$this -> success('删除评价成功！');
			// $this -> assign('name', $name);
			// return $this -> fetch('comment_info/index');
		} else {
			return $this -> error('删除评价失败！' . $res);
		}
	}
}