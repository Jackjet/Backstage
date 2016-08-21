<?php
namespace Admin\Controller;
use Common\Controller\AuthController;
use Think\Auth;
use Org\Net\Http;

/**
 * 应用控制器
 */
class AppController extends AuthController {
	public function wish_manage() {
		$this -> display();
	}

	public function wish_list() {
		//获取查询条件
		$where;
		if (!empty(I('account'))) {
			$where['account'] = array("like", "%" . I('account') . "%");
		}
		if (I('status') != '') {
			$where['status'] = I('status');
		}

		//获取分页信息
		$index = I("pageIndex");
		$size = I("pageSize");

		$user = M('wish');

		$count = $user -> where($where) -> count();
		$data = $user -> where($where) -> order('id') -> limit($index * $size, $index * $size + $size) -> select();
		foreach ($data as $k => $v) {
			$data[$k]['time'] = date("Y-m-d H:m:s", $data[$k]['time']);
			//			$data[$k]['content'] = html_entity_decode($data[$k]['content']);
		}
		$result = array("total" => $count, "data" => $data);
		$this -> ajaxReturn($result, 'JSON');
	}

	//删除愿望
	public function wish_delete() {
		$returnData['success'] = false;
		$where['id'] = I('id');
		$m = M('wish');
		if ($m -> where($where) -> delete()) {
			$returnData['success'] = true;
		} else {
			$returnData['info'] = "删除出错了！";
		}
		$this -> ajaxReturn($returnData);
	}

	public function file_manage() {
		$this -> display();
	}

	public function file_upload() {
		$this -> display();
	}

	public function file_list() {
		//获取查询条件
		$where;
		if (!empty(I('key'))) {
			$where['topic'] = array("like", "%" . I('key') . "%");
		}

		//获取分页信息
		$index = I("pageIndex");
		$size = I("pageSize");

		$user = M('file');

		$count = $user -> where($where) -> count();
		$data = $user -> where($where) -> order('id') -> limit($index * $size, $index * $size + $size) -> select();
		foreach ($data as $k => $v) {
			$data[$k]['time'] = date("Y-m-d H:m:s", $data[$k]['time']);
		}
		$result = array("total" => $count, "data" => $data);
		$this -> ajaxReturn($result, 'JSON');
	}

	public function upload() {
		$returnData['success'] = false;
		$upload = new \Think\Upload();
		// 实例化上传类
		$upload -> maxSize = 3145728;
		// 设置附件上传大小
		$upload -> exts = array('jpg', 'gif', 'png', 'jpeg', 'docx', 'txt');
		// 设置附件上传类型
		$upload -> rootPath = './Public/uploads/';
		// 设置附件上传根目录
		$upload -> savePath = '';
		// 设置附件上传（子）目录
		// 上传文件
		$info = $upload -> upload();
		if (!$info) {// 上传错误提示错误信息
			$returnData['info'] = $upload -> getError();
		} else {// 上传成功
			$file = M('file');
			$data['name'] = $info['myfile']['name'];
			$data['path'] = $info['myfile']['savepath'] . $info['myfile']['savename'];
			$data['topic'] = I('topic');
			$data['type'] = $info['myfile']['type'];
			$data['size'] = $info['myfile']['size'] / 1024;
			$data['time'] = time();
			if ($file -> add($data)) {
				$returnData['success'] = true;
			} else {
				$returnData['info'] = "上传成功，但是数据库存储出错啦！";
			}
		}
		$this -> ajaxReturn($returnData);
	}

	public function download() {
		$uploadpath = './Public/uploads/';
		$where['id'] = I('id');
		$file = M('File');
		$f = $file -> where($where) -> find();
		if (!$f)//如果查询不到文件信息
		{
			$this -> error("文件不存在！", '', 1);
		} else {
			$path = $f['path'];
			$showname = $f['name'];
			$filepath = $uploadpath . $path;
			Http::download($filepath);
		}
	}

	public function file_delete() {
		$returnData['success'] = false;
		$uploadpath = './Public/uploads/';
		
		$where['id'] = I('id');
		$file = M('File');
		$f = $file -> where($where) -> find();
		$path = $f['path'];
		$showname = $f['name'];
		$filepath = $uploadpath . $path;
		if(!is_file($filepath) || unlink($filepath)) {
			$file -> delete(I('id'));
			$returnData['success'] = true;
		} else {
			$returnData['info'] = '删除出错了！';
		}
		$this -> ajaxReturn($returnData);
	}

	public function model_manage() {
		$this -> display();
	}

	public function swf_upload() {
		$path = "./Public/uploads/";
		$Fdata = $_FILES["Fdata"]["name"];
		$file = iconv("UTF-8", "gb2312", $path . $Fdata);
		$result = move_uploaded_file($_FILES["Fdata"]["tmp_name"], $file);
		if ($result == true) {
			date_default_timezone_set("PRC");
			$date = date("Y-m-d H:i:s");
			echo $Fdata . $date;
		}
	}

	/**
	 * webuploader 上传文件
	 */
	public function ajax_upload() {
		// 根据自己的业务调整上传路径、允许的格式、文件大小
		ajax_upload('/Upload/');
	}

	/**
	 * webuploader 上传demo
	 */
	public function webuploader() {
		// 如果是post提交则显示上传的文件 否则显示上传页面
		if (IS_POST) {
			$image = I('post.image');
			// 判断是否有文件上传
			if (empty($image)) {
				die('没有上传文件');
			}
			echo '上传成功路径为：' . $image;
		} else {
			$this -> display();
		}
	}

}
