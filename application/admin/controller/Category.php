<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\admin\model\CommidityType;

class Category extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {   
	$commidity_types = CommidityType::all();
	$this->assign('list',$commidity_types);	
        return $this->fetch('cate');
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
        $category_name=input('request.category_name');
	$commidity_type= new CommidityType;
	$commidity_type->name=$category_name;
	$commidity_type->update_time=time();
	$commidity_type->create_time=time();
	if($commidity_type->save())
	{
	    return $this->index();
	}
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
        $commidity_type = CommidityType::get($id);
	$commidity_type->delete();
	return $this->index();
    }
}
