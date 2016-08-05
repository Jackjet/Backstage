<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller {
	public function index(){
		$wish = M('wish')->select();
		foreach ($wish as $k => $v) {
			$wish[$k]['content'] = html_entity_decode($wish[$k]['content']);
		}
		$this->assign('wish',$wish)->display();
	}
	public function handle(){
		$returnData['success'] = false;
		$data = array (
			'username' => I('username'),
			'content' => html_entity_decode(I('content')),
			'time' => time()
		);
		if ($id = M('wish')->data($data)->add()){
			$data['id'] = $id;
//			$data['content'] = replace_expression($data['content']);
			$data['time'] = date('y-m-d', $data['time']);
			$returnData['success'] = true;
			$returnData['info'] = $data;
			$this->ajaxReturn($returnData,'json');
		}else{
			$returnData['info'] = "添加失败！";
			$this->ajaxReturn($returnData, 'json');
		}
	}
}