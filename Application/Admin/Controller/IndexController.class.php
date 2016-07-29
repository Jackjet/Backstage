<?php
namespace Admin\Controller;
use Common\Controller\AuthController;
use Think\Auth;

class IndexController extends AuthController {

	//首页
	public function index() {
		$this -> display();
	}
	
	//菜单
	public function menu() {
		$m = M('auth_rule');
		$field = 'id,name,title';
		$where['pid'] = 0;
		$where['status'] = 1;
		$data = $m -> field($field) -> where($where) -> select();
		$auth = new Auth();
		//没有权限的菜单不显示
		foreach ($data as $k => $v) {
			if (session('aid') != 1 && !$auth -> check($v['name'], session('aid'))) {
				unset($data[$k]);
			} else {
				// status = 1    为菜单显示状态
				$data[$k]['children'] = $m -> field($field) -> where('pid=' . $v['id'] . ' AND status=1') -> select();
				foreach ($data[$k]['children'] as $k2 => $v2) {
					if (!$auth -> check($v2['name'], session('aid')) && session('aid') != 1) {
						unset($data[$k]['children'][$k2]);
					}
				}
			}
		}
		$this -> ajaxReturn($data);
	}

	//主页面
	public function main() {
		$this -> display();
	}

	//修改密码
	public function update_pwd() {
		if (!empty($_POST)) {
			$returnData['success'] = false;

			$m = M('user');
			$where['id'] = session('aid');
			$where['password'] = md5(I('old_pwd'));
			$new_pwd = md5(I('new_pwd'));
			$data = $m -> field('id') -> where($where) -> find();
			if (empty($data)) {
				$returnData['info'] = '原密码错误!';
			} else {
				$result = $m -> where('id=' . $where['id']) -> data('password=' . $new_pwd) -> save();
				if ($result) {
					session('aid', null);
					session('account', null);
					$returnData['success'] = true;
				} else {
					$returnData['info'] = '原密码错误!';
				}
			}
			$this -> ajaxReturn($returnData);
		} else {
			$this -> display();
		}
	}

	//循环删除目录和文件函数
	public function delDirAndFile($dirName) {
		if ($handle = opendir("$dirName")) {
			while (false !== ($item = readdir($handle))) {
				if ($item != "." && $item != "..") {
					if (is_dir("$dirName/$item")) {
						delDirAndFile("$dirName/$item");
					} else {
						unlink("$dirName/$item");
					}
				}
			}
			closedir($handle);
			if (rmdir($dirName))
				return true;
		}
	}

	//清除缓存
	public function clear_cache() {
		$returnData['success'] = false;

		$str = I('clear');
		//防止搜索到第一个位置为0的情况
		if ($str) {
			//strpos 参数必须加引号
			//删除Runtime/Cache/admin目录下面的编译文件
			if (strpos("'" . $str . "'", '1')) {
				$dir = APP_PATH . 'Runtime/Cache/Admin/';
				$this -> delDirAndFile($dir);
			}
			//删除Runtime/Cache/Home目录下面的编译文件
			if (strpos("'" . $str . "'", '2')) {
				$dir = APP_PATH . 'Runtime/Cache/Home/';
				$this -> delDirAndFile($dir);
			}
			//删除Runtime/Data/目录下面的编译文件
			if (strpos("'" . $str . "'", '3')) {
				$dir = APP_PATH . 'Runtime/Data/';
				$this -> delDirAndFile($dir);
			}
			//删除Runtime/Temp/目录下面的编译文件
			if (strpos("'" . $str . "'", '4')) {
				$dir = APP_PATH . 'Runtime/Temp/';
				$this -> delDirAndFile($dir);
			}
			$returnData['success'] = true;
			$this -> ajaxReturn($returnData);
		} else {
			$this -> display();
		}
	}

	//退出登录
	public function logout() {
		session('aid', null);
		//注销 uid ，account
		session('account', null);
		$this -> success('退出登录成功', U('Public/login'));
	}

}
