<?php
namespace app\home\model;
use think\Model;
use think\Db;

class Address extends Model
{
	const TB_Address = 'address';
	protected $pk = 'id';
}