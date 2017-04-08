<?php
namespace app\home\controller;
use think\Controller;
use think\Request;
use app\home\model\Deal;

class Upload extends Controller
{
	// public function upload()
	// {
	// 	$login = new Login();
	// 	$name = $login -> getUserSessionInfo()['name'];
	// 	$userId = $login -> getUserSessionInfo()['id'];
	// 	$files = request() -> file('upload-file');
	// 	$flag = 0;
	// 	foreach ($files as $file) {
	// 		$path = ROOT_PATH . 'public' . DS . 'homeuploads' . DS . date("Ymd", time());
	// 		$info = $file -> validate(['ext' => 'txt,doc,docx,xls,xlsx,ppt,pptx,pdf,jpg,jpeg,gif,bmp,png']) -> move($path, '');
	// 		if ($info) {
	// 			// echo $info -> getExtension();
	// 			// echo $info -> getSaveName();
	// 			// $userFiles[] = $info -> getFilename();
	// 			$userFiles[] = $path . DS . $info -> getSaveName();
	// 		} else {
	// 			return $this -> error($file -> getError());
	// 		}
	// 		$flag = 1;
	// 	}
	// 	if ($flag) {
	// 		// $this -> assign(
	//   //   		[
	//   //   			"userFiles" => json_encode($userFiles),
	//   //   		]
 //   //  		);
	// 		// return json_encode($userFiles);
	// 		// return $this -> fetch("commidities_info/commiditiesInfoByPrint");
	// 		// return $this -> success('Files upload successful!');
	// 		return '{"userFiles":"' . json_encode($userFiles) . '","error":"errorFile"}';
	// 	}
	// }

	//上传文件
	public function upload()
	{
		$login = new Login();
		$name = $login -> getUserSessionInfo()['name'];
		$userId = $login -> getUserSessionInfo()['id'];

		$fileName = $_FILES['uploadfile']['name']; 
	    $fileSize = $_FILES['uploadfile']['size']; 
	    if ($fileName != "") { 
	        if ($fileSize > 20 * 1024 * 1024) { //限制上传大小 
	            echo '{"status":0,"content":"文件大小不能超过20M"}';
	            exit; 
	        } 
	        $type = strstr($fileName, '.'); //限制上传格式 
	        if ($type != ".pdf" && $type != ".doc" && $type != ".docx" && $type != ".jpg" && $type != ".gif" && $type != ".png" && $type != ".txt" && $type != ".xls" && $type != ".xlsx" && $type != ".ppt" && $type != ".pptx" && $type != ".bmp") {
	            echo '{"status":2,"content":"文件格式不正确！<br>图片仅支持&nbsp;<span style=\"color:#4CA2D3\">jpg、gif、bmp、png&nbsp;</span>等格式<br>文档仅支持&nbsp;<span style=\"color:#4CA2D3\">doc、docx、ppt、pptx、txt、pdf、xls、xlsx&nbsp;</span>等格式"}';
	            exit; 
	        }
	        //上传路径 
	        $path = ROOT_PATH . 'public' . DS . 'homeuploads' . DS . 'userFiles' . DS . date("YmdH", time()) . $userId . DS;
	        if (! is_readable($path)) {
	        	is_file($path) or mkdir($path, 0777);
	        } 
	        $filePath = $path. $fileName; 
	        move_uploaded_file($_FILES['uploadfile']['tmp_name'], $filePath); 
	    } 
	    $size = round($fileSize/1024,2); //转换成kb 
	    echo '{"status":1,"name":"'.$fileName.'","url":"'.$filePath.'","size":"'.$size.'","content":"上传成功"}'; 
	}

	//上传用户头像
	public function uploadPhoto()
	{
		$login = new Login();
		$name = $login -> getUserSessionInfo()['name'];
		$userId = $login -> getUserSessionInfo()['id'];

		$fileName = $_FILES['uploadphoto']['name']; 
	    $fileSize = $_FILES['uploadphoto']['size']; 

	    if ($fileSize > 2 * 1024 * 1024) { //限制上传大小 
            echo '{"status":0,"content":"图片大小不能超过2M"}';
            exit; 
        } 
        $type = strstr($fileName, '.'); //限制上传格式 
        if ($type != ".jpg" && $type != ".gif" && $type != ".png" && $type != ".bmp") {
            echo '{"status":2,"content":"图片格式不正确！<br>仅支持&nbsp;<span style=\"color:#4CA2D3\">jpg、gif、bmp、png&nbsp;</span>等格式<br>"}';
            exit; 
        }
        // $fileName = $name . $type;
        //上传路径 
        $path = ROOT_PATH . 'public' . DS . 'homeuploads' . DS . 'userPhoto' . DS . $name . DS;
        if (! is_readable($path)) {
        	is_file($path) or mkdir($path, 0777);
        } 
        $filePath = $path. $fileName; 
        move_uploaded_file($_FILES['uploadphoto']['tmp_name'], $filePath); 
	    $size = round($fileSize/1024,2); //转换成kb 
	    echo '{"status":1,"name":"'.$fileName.'","url":"'.$filePath.'","size":"'.$size.'","content":"上传成功"}'; 
	}
}