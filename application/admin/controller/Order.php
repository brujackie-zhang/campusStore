<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;
use app\admin\model\Deal;

class Order extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
	$order_id=input('request.orderId');
	$user_name=input('request.userName');
	$sql="select deal.id,deal.deal_number,commidity.name as commidity_name,user.nickname,deal.commidity_num,deal.price,deal.discount,deal.total,deal.pay_method,deal.delivery_method,deal.status,FROM_UNIXTIME(deal.create_time,'%Y-%m-%d %H:%i:%S') as create_time from deal inner join commidity on deal.commidity_id=commidity.id inner join user on deal.user_id=user.id";
	if($order_id!=NULL||$user_name!=NULL)
	{
	    $sql.=" where";
	}
	if($order_id!=NULL)
	{
	    $sql.=" deal.deal_number=$order_id";
	}
	if($user_name!=NULL&&$order_id!=NULL)
	{
	    $sql.=" and";
	}
	if($user_name!=NULL)
	{
	    $sql.=" user.nickname='$user_name'";
	}
	$order_list=Db::query($sql);
	$this->assign('list',$order_list);
        return $this->fetch('list');
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $order = Deal::get($id);
	$user->delete();
	return $this->index();
    }
}
