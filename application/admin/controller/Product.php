<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\admin\model\Commidity;
use app\admin\model\CommidityType;

class Product extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {	
	$product_name=input('request.keywords');
	if($product_name!=NULL)
	{   
	    $commidity = new Commidity;
	    $product_list=$commidity->where('name','like','%'.$product_name.'%')->select();
	}else{
	    $product_list = Commidity::all();
	}
	$this->assign('list',$product_list);
        return $this->fetch('list');
    }
    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
	$product_types=$this->getProductType();
	$this->assign('product_types',$product_types);
        return $this->fetch('add');
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $product = new Commidity;
        $product->name = input('request.product_name');
        $product->image = input('request.img');
        $product->commidity_type_id = input('request.product_kind');
        $product->introduction = input('request.product_desc');
        $product->produce_area = input('request.product_origin');
        $product->stocks = input('request.product_count');
        $product->price = input('request.product_price');
        $product->create_by = input('request.product_adder');
	$product->update_time = time();
        $product->create_time = time();
        if($product->save())
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
        $product = Commidity::get($id);
	$product_types = $this->getProductType();
	$this->assign('product_types',$product_types);
	$this->assign('product',$product);
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
        $product = Commidity::get($id);
	$product->name = input('request.product_name');
	$product->image = input('request.img');
	$product->commidity_type_id = input('request.product_kind');
	$product->introduction = input('request.product_desc');
	$product->produce_area = input('request.product_origin');
	$product->stocks = input('request.product_count');
	$product->price = input('request.product_price');
	$product->update_by = input('request.product_updater');
	$product->update_time = time();
	if($product->save())
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
        $product = Commidity::get($id);
	$product->delete();
	return $this->index();
    }
    public function getProductType()
    {
	return CommidityType::all();
    }
    public function upload()
    {
	// 获取表单上传文件 例如上传了001.jpg
    $file = request()->file('image');
    // 移动到框架应用根目录/public/uploads/ 目录下
    $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
    if($info){
        // 成功上传后 获取上传信息
        // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
        echo $info->getSaveName();
    }else{
        // 上传失败获取错误信息
        echo $file->getError();
    }	
    }
}
