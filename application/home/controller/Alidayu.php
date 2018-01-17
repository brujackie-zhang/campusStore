<?php
namespace app\home\controller;
use app\home\model\AlidayuSend;
use think\Controller;
use think\Session;

class Alidayu extends Controller
{
	//登录界面发送验证码
	public function sms()
	{
		if(request()->isPost()){
			$captcha = strval(rand(100000, 999999));
			session('captcha', $captcha);
			session('userName', input('post.userName'));
			$userName = input('post.userName');
			$send = new AlidayuSend;
			$result = $send -> sms(
				[
					'param'    => ['captcha' => $captcha, 'name' => $userName],
					'mobile'   => input('post.mobile/s','','trim,strip_tags'),
					'template' => 'SMS_62350190',
				]
			);
			if($result !== true){
				return $this->error($result);
			}
			return $this->success('验证码发送成功！');
		}
		return $this->fetch();
	}

	//找回密码发送验证码
	public function smsForLook()
	{
		if(request()->isPost()){
			$captcha = strval(rand(100000, 999999));
			session('captchaLook', $captcha);
			$userName = Session::get('userInfo.name');
			$send = new AlidayuSend;
			$result = $send -> sms(
				[
					'param'    => ['captcha' => $captcha, 'name' => $userName],
					'mobile'   => input('post.mobile/s','','trim,strip_tags'),
					'template' => 'SMS_62350190',
				]
			);
			if($result !== true){
				return $this->error($result);
			}
			return $this->success('验证码发送成功！');
		}
		return $this->fetch();
	}

	//登陆界面验证验证码
	public function verifyCaptcha()
	{
		$captcha = Session::get('captcha') ? Session::get('captcha') : "123456";
		$verifyCaptcha = input('post.verifyCaptcha');
		if ($captcha == $verifyCaptcha) {
			return $this -> success("验证码验证成功！");
		} else {
			return $this -> error("验证码验证失败！" . $captcha);
		}
	}

	//找回密码验证验证码
	public function verifyCaptchaLook()
	{
		$captcha = Session::get('captchaLook') ? Session::get('captchaLook') : "123456";
		$verifyCaptcha = input('post.verifyCaptcha');
		if ($captcha == $verifyCaptcha) {
			return $this -> success("验证码验证成功！");
		} else {
			return $this -> error("验证码验证失败！" . $captcha);
		}
	}
}