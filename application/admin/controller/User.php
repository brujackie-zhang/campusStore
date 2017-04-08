<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\admin\model\User as BuyUser;

class User extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
	$user_name=input('request.userName');
	if($user_name!=NULL)
	{
	    $user=new BuyUser;
	    $user_list=$user->where('nickname','like','%'.$user_name.'%')->select();
	}else{
	    $user_list=BuyUser::all();
	}
	$this->assign('list',$user_list);
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
        $user=BuyUser::get($id);
	$this->assign('user_info',$user);
	return $this->fetch('edit');
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
	$user_nickname=input('request.user_nickname');
	$user_password=md5(input('request.user_password'));
	$user_sex=input('request.user_sex');
	$user_real_name=input('request.user_real_name');
	$user_id_card=input('request.user_id_card');
	$user_phone=input('request.user_phone');
	$user_school=input('request.user_school');

	$user=BuyUser::get($id);
	$user->nickname=$user_nickname;
	$user->password=$user_password;
	$user->sex=$user_sex;
	$user->real_name=$user_real_name;
	$user->id_card=$user_id_card;
	$user->mobile_phone=$user_phone;
	$user->school=$user_school;
	if($user->save())
	{
	    return $this->index();
	}
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $user=BuyUser::get($id);
	$user->delete();
	return $this->index();
    }
}
