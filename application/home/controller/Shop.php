<?php
namespace app\home\controller;
use think\View;
use think\Controller;
use think\Db;
use think\Request;
use think\Session;
use app\home\model\CommidityParent;
use app\home\model\CommidityType;
use app\home\model\Commidity;
use app\home\model\Comment;

error_reporting(0);
class Shop extends Controller
{
	//获取导航栏商品分类信息
	public function getCommidities()
	{
		$commParentModel = new CommidityParent; 	//实例化父类商品
		$commTypeModel   = new CommidityType;		//实例化子类商品
		$commidityModel  = new Commidity; 			//实例化商品

		$result = $commParentModel -> getCommidityParentName();
		if ($result) {
			foreach ($result as $v) {
				$pId = $v['id'];
				$ctR[$v['name']] = $commTypeModel -> getCommidityTypeName($pId);
				if ($ctR[$v['name']]) {
					foreach ($ctR[$v['name']] as $vv) {
						$tId = $vv['id'];
						$cR[$vv['name']] = $commidityModel -> getCommidityInfoByType($tId);
						if ($cR[$vv['name']]) {
							$ctR[$v['name']] = $cR;
						} else {
							$ctR[$v['name']] = $v['name'];
						}
					}
				}
				unset($cR);
			}
			// print_r($ctR);
			return $ctR;
		} else {
			return $this -> error('暂无数据！');
		}
	}

	//获取商品展示页商品信息
	public function commidityInfoForIndex()
	{
		$commidity = new Commidity;		//实例化商品
		$comment   = new Comment;		//实例化评价

		$total = $commidity -> getCommidityTotal();
		$result = $commidity -> commidityInfoForIndex(12, $total);
		//设置商品信息session
		session('commiditiesInfo', $result);
		if ($result) {
			foreach ($result as $v) {
				$commidityId = $v['id'];
				$commentTotal[$commidityId] = $comment -> getCommentTotal($commidityId, []);
				$commentTotal[$commidityId] = $commentTotal[$commidityId] ? $commentTotal[$commidityId] : '0';
			}
		}
		$page = $result -> render();
		$coResult = $this -> getCommidities();
		if ($result) {
			$page = $this -> assign(
				[
					'list'         => $coResult,
					'commidities'  => $result,
					'commentTotal' => $commentTotal,
					'page'         => $page,
				]
			);
			return $page;
		} else {
			return $this -> error('暂无数据！');
		}
	}
}
