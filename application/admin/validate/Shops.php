<?php
namespace app\admin\validate;
use think\Validate;

class Shops extends Validate
{
	protected $rule = [
		'name'        => 'require|max:25',
		'sevices'     => 'require|max:100',
		'description' => 'require|max:200',
		'address'     => 'require',
		'mobile'      => 'require',
		'mobile'      => ['regex' => '/^(13|15|17|18)[0-9]{9}/'],
		'qq'          => 'require',
		'alipay'      => 'require',
		'wxpay'       => 'require',
		'on_duty'     => 'require',
		'off_duty'    => 'require',
	];

	protected $message = [
		'name.require'        => '店铺名不可为空',
		'name.max'            => '店铺名长度不能超过25个字符',
		'sevices.require'     => '店铺服务不可为空',
		'sevices.max'         => '店铺服务长度不能超过100个字符',
		'description.require' => '店铺描述不可为空',
		'description.max'     => '店铺描述长度不能超过200个字符',
		'address.require'     => '店铺地址不可为空',
		'mobile.require'      => '手机号不可为空',
		'mobile.regex'        => '手机号格式不正确',
		'qq.require'          => 'QQ号不可为空',
		'alipay.require'      => '支付宝帐号不可为空',
		'wxpay.require'       => '微信帐号不可为空',
		'on_duty.require'    => '开业时间不可为空',
		'off_duty.require'    => '关门时间不可为空',
	];
}