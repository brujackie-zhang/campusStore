<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]

// 定义应用目录

//项目根目录
define('APP_PATH', __DIR__ . '/../application/');
//分页当前页
define('NOW_PAGE', 1);
//分页每页展示量
define('PER_PAGE', 10);
//项目所用CSS存放目录
define('CSS', '/static/common/css/');
//项目所用JS存放目录
define('JS', '/static/common/js/');
//项目所用图标存放目录
define('IMG', '/static/common/img/');
//项目所用icon存放目录
define('ICON', '/static/common/icon/');
//项目所用商品图片存放目录
define('PIC', '/static/common/pic/');
//用户头像存放目录
define('USER_PHOTO', '/homeuploads/userPhoto/');
// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';
