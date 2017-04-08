<?php
namespace app\home\controller;
use think\Controller;
use app\home\model\CommidityParent;

class Search extends controller
{
	//获取系统中的商品父类别名称
	public function getCommidityParentName()
	{
		$parent = new CommidityParent();
		$result = $parent -> getCommidityParentName();
		$login = new Login();
		$name = $login -> getUserSessionInfo()['name'];

		if ($result) {
			return $result;
		} else {
			return $this -> error('暂时没有数据！o(╯□╰)o');
		}
	}
}