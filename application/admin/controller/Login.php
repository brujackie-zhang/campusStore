<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\View;
use think\Input;
use think\Captcha;
use think\Validate;
use app\admin\model\AdminUser;

class Login extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
	return $this->fetch('login',['isError' => 1]);
    }
    
    public function login()
    {
	//$view = new View();
	$name = input('request.name');
	$password  = input('request.password');
	$data = input('request.captcha');
	if(!captcha_check($data))
	{
            return view('login', [
		'isError' => 0,
   	        'isCaptchaCorrect'  => 0,
		'isHasUser' => 1
	    ]);
        }
	$user = new AdminUser;
	if($user->login($name,$password)){
	    return $this->redirect('index/index');
	}else{
	    return view('login', [
		'isError' => 0,
		'isCaptchaCorrect' => 1,
                'isHasUser'  => 0,
            ]);
	}
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
        //
    }
}
