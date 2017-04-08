<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\admin\model\AdminUser;

class Admin extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        return $this->fetch('pass',['user_name' => session('ext_user')['name']]);
    }
    
    public function updatePassWord()
    {
        $oldpassword = input('request.mpass');
        $newpassword  = input('request.newpass');
        $newpassword1  = input('request.renewpass');
        $name=session('ext_user')['name'];
        $user = new AdminUser;
        $changepsw=$user->search($name);
        // dump($changepsw['password']);
        $password=$changepsw['password'];
        if ($password==$oldpassword ) {
            if ($newpassword==$newpassword1) {
            $updatepassword=$user->updatepassword($name,$newpassword);
            if ($updatepassword) {
            session("ext_user", $changepsw);
            return $this->success('修改成功', 'admin/index');
            }else{
                return $this->error("修改密码失败",'admin/index');
            }
            }else{
                return $this->error("两次输入密码不一致",'admin/index');
            }
        }else{
            return $this->error("原密码输入错误",'admin/index');
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
