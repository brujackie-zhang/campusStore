<?php

namespace app\admin\model;

use think\Model;


class AdminUser extends Model
{
    public function login($name,$password)
    {
	$where['name'] = $name;
        $where['password'] = $password;

        $user=AdminUser::where($where)->find();
        if ($user) {
            unset($user["password"]);
            session("ext_user", $user);
            return true;
        }else{
            return false;
        }
    }
    // 查询一条数据
    public function search($name){
        $where['name'] = $name;
        $user=AdminUser::where($where)->find();
        return $user;
    }

    //更改用户密码
    public function updatepassword($name,$newpassword){
        $where['name'] = $name;
        $user=AdminUser::where($where)->update(['password' => $newpassword]);
        if ($user) {
            return true;
        }else{
            return false;
        }
    }
}
