<?php
namespace app\home\validate;
use think\Validate;

class Address extends Validate
{
	protected $rule = [
		'province' => 'require',
		'city'     => 'require',
		'address'  => 'require',
		'receiver' => 'require',
		'mobile'   => 'require',
		'mobile'   => ['regex' => '/^(13|15|17|18)[0-9]{9}/'],
	];

	protected $message = [
		'province.require' => '省份不可为空',
		'city.require'     => '城市不可为空',
		'address.require'  => '地址不可为空',
		'receiver.require' => '收货人不能为空',
		'mobile.require'   => '手机号不能为空',
		'mobile.regex'     => '手机号格式不正确',
	];

	protected $scene = [
		'add'     => ['mobile'],
	];
}