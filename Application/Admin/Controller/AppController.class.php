<?php
namespace Admin\Controller;
use Common\Controller\AuthController;
use Think\Auth;

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

	public function model_manage() {
		$this -> display();
	}

	public function upload() {
		$upload = new \Think\Upload();
		// 实例化上传类
		$upload -> maxSize = 3145728;
		// 设置附件上传大小
		$upload -> exts = array('jpg', 'gif', 'png', 'jpeg');
		// 设置附件上传类型
		$upload -> rootPath = './Public/Admin/uploads/';
		// 设置附件上传根目录
		$upload -> savePath = '';
		// 设置附件上传（子）目录
		// 上传文件
		$info = $upload -> upload();
		if (!$info) {// 上传错误提示错误信息
			$this -> error($upload -> getError());
		} else {// 上传成功
			$this -> success('上传成功！');
		}
	}

	/**
	 * webuploader 上传文件
	 */
	public function ajax_upload() {
		// 根据自己的业务调整上传路径、允许的格式、文件大小
		ajax_upload('./Public/Admin/uploads/');
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
