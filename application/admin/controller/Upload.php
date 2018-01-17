<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Shops;

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

class Upload extends Controller
{
    //webuploader插件上传
    public function upload()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit;
        }
        if ( !empty($_REQUEST['debug']) ) {
            $random = rand(0, intval($_REQUEST['debug']) );
            if ( $random === 0 ) {
              header("HTTP/1.0 500 Internal Server Error");
              exit;
            }
        }
        @set_time_limit(5 * 60);
        // $targetDir = 'uploads'.DIRECTORY_SEPARATOR.'file_material_tmp';
        // $uploadDir = 'uploads'.DIRECTORY_SEPARATOR.'file_material'.DIRECTORY_SEPARATOR.date('Ymd');
        $targetDir = ROOT_PATH . 'public' . DS . 'static' . DS . 'common' . DS . 'pic_tmp';
        $uploadDir = ROOT_PATH . 'public' . DS . 'static' . DS . 'common' . DS . 'pic';
        $cleanupTargetDir = true;
        $maxFileAge = 5 * 3600;

        if (!file_exists($targetDir)) {
            @mkdir($targetDir);
        }
        if (!file_exists($uploadDir)) {
            @mkdir($uploadDir);
        }
        if (isset($_REQUEST["name"])) {
            $fileName = $_REQUEST["name"];
        } elseif (!empty($_FILES)) {
            $fileName = $_FILES["file"]["name"];
        } else {
            $fileName = uniqid("file_");
        }
        $oldName = $fileName;
        $filePath = $targetDir . DS . $fileName;
        $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;
        if ($cleanupTargetDir) {
            if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" :   "id"}');
            }
            while (($file = readdir($dir)) !== false) {
                $tmpfilePath = $targetDir . DS . $file;
                if ($tmpfilePath == "{$filePath}_{$chunk}.part" || $tmpfilePath == "{$filePath}_{$chunk}.parttmp") {
                    continue;
                }
                if (preg_match('/\.(part|parttmp)$/', $file) && (@filemtime($tmpfilePath) < time() - $maxFileAge)) {
                    @unlink($tmpfilePath);
                }
            }
            closedir($dir);
        }
     
        if (!$out = @fopen("{$filePath}_{$chunk}.parttmp", "wb")) {
            die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
        }
        if (!empty($_FILES)) {
            if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}  ');
            } 
            if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}'  );
            }
        } else {
            if (!$in = @fopen("php://input", "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}'  );
            }
        }
        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }
        @fclose($out);
        @fclose($in);
        rename("{$filePath}_{$chunk}.parttmp", "{$filePath}_{$chunk}.part");
        $index = 0;
        $done = true;
        for( $index = 0; $index < $chunks; $index++ ) {
            if ( !file_exists("{$filePath}_{$index}.part") ) {
                $done = false;
                break;
            }
        }

        if ($done) {
            $pathInfo = pathinfo($fileName);
            $hashStr = substr(md5($pathInfo['basename']),8,16);
            $hashName = time() . $hashStr . '.' . $pathInfo['extension'];
            // $uploadPath = $uploadDir . DS . $hashName;
            $uploadPath = $uploadDir . DS . $oldName;
            $imagePath = '/static/common/pic/' . $oldName;
       
            if (!$out = @fopen($uploadPath, "wb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}  ');
            }
            if ( flock($out, LOCK_EX) ) {
                for( $index = 0; $index < $chunks; $index++ ) {
                  if (!$in = @fopen("{$filePath}_{$index}.part", "rb")) {
                    break;
                  }
                  while ($buff = fread($in, 4096)) {
                    fwrite($out, $buff);
                  }
                  @fclose($in);
                  @unlink("{$filePath}_{$index}.part");
                }
                flock($out, LOCK_UN);
            }
            @fclose($out);
            $response = [
                'success'      => true,
                'oldName'      => $oldName,
                'filePath'     => $uploadPath,
                'fileSuffixes' => $pathInfo['extension'],
            ];
            // die($oldName);
            // die($imagePath);
            die("upload success!");
        }

        die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
    }
}