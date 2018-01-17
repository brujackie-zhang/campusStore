<?php
namespace app\admin\validate;
use think\Validate;

class Commidity extends Validate
{
	protected $rule = [
		'name'         => 'require|max:25',
		'introduction' => 'require|max:200',
		'stocks'       => 'require',
		'price'        => 'require',
		'discount'     => 'require',
	];

	protected $message = [
		'name.require'         => '商品名不可为空',
		'name.max'             => '商品名长度不能超过25个字符',
		'introduction.require' => '商品介绍不可为空',
		'introduction.max'     => '商品介绍长度不能超过200个字符',
		'stocks.require'       => '商品库存不可为空',
		'price.require'        => '商品价格不可为空',
		'discount.require'     => '商品折扣不可为空',
	];
}