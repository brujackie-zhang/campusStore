<?php
namespace app\home\controller;
use think\View;
use think\Controller;
use think\Db;
use app\home\model\CommidityParent;
use app\home\model\CommidityType;
use app\home\model\Commidity;

class Shop extends Controller
{
	public function getCommidities()
	{
		$cp = new CommidityParent;
		$ct = new CommidityType;
		$c = new Commidity;
		$result = $cp -> getCommidityParentName();
		if ($result) {
			foreach ($result as $v) {
				$pId = $v['id'];
				$ctR[$v['name']] = $ct -> getCommidityTypeName($pId);
				if ($ctR[$v['name']]) {
					foreach ($ctR[$v['name']] as $vv) {
						$tId = $vv['id'];
						$cR[$vv['name']] = $c -> getCommidityInfoByType($tId);
						if ($cR[$vv['name']]) {
							$ctR[$v['name']] = $cR;
						} else {
							$ctR[$v['name']] = $v['name'];
						}
					}
				}
				unset($cR);
			}
			return $ctR;
		} else {
			return $this -> error('暂无数据！');
		}
		// if ($result) {
		// 	$view = new View();
		// 	$view -> assign([
		// 		'list' => $ctR,
		// 	]);
		// 	return $view -> fetch('shop/shop');
		// } else {
		// 	return $this -> error('暂无数据!');
		// }
	}
}
