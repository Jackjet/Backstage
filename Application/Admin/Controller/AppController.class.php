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
}
