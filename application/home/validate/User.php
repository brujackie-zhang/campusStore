<?php
namespace app\home\validate;
use think\Validate;

class User extends Validate
{
	protected $rule = [
		'name'           => 'require|max:25',
		'nickname'       => 'require|max:25',
		'password'       => 'require|min:6',
		'newPassword'    => 'require|min:6',
		'verifyPassword' => 'require|min:6',
		'mobile'         => ['regex' => '/^(13|15|17|18)[0-9]{9}/'],
		'email'          => 'require|email',
		'question'       => 'require',
		'answer'         => 'require',
		'sex'            => 'require',
		'address'        => 'require',
		'is_student'     => 'number|between:0,1',
		'province'       => 'require',
		'city'           => 'require',
		'captcha'        => 'require|captcha',
	];

	protected $message = [
		'name.require'           => '用户名不可为空',
		'province.require'       => '省份不可为空',
		'city.require'           => '城市不可为空',
		'question.require'       => '密保问题不可为空',
		'answer.require'         => '密保答案不可为空',
		'sex.require'            => '性别不可为空',
		'address.require'        => '地址不可为空',
		'captcha.require'        => '验证码不可为空',
		'captcha.captcha'        => '验证码错误',
		'is_student.require'     => '是否学生不可为空',
		'is_student.between'     => '是否学生值在0-1之间',
		'name.max'               => '用户名长度不能超过25个字符',
		'nickname.require'       => '昵称不可为空',
		'nickname.max'           => '昵称长度不能超过25个字符',
		'password.require'       => '密码不可为空',
		'password.min'           => '密码不得少于6位',
		'verifyPassword.require' => '密码不可为空',
		'verifyPassword.min'     => '密码不得少于6位',
		'newPassword.require'    => '密码不可为空',
		'newPassword.min'        => '密码不得少于6位',
		'mobile.regex'           => '手机号格式不正确',
		'email.require'          => '邮箱不能为空',
		'email.email'            => '邮箱格式不正确',
	];

	protected $scene = [
		'edit'     => ['name', 'email'],
		'test'     => ['name'],
		'password' => ['password', 'newPassword'],
		'register' => ['name', 'question', 'answer', 'sex', 'address', 'captcha', 'is_student', 'nickname', 'password', 'verifyPassword', 'province', 'city'],
	];
}