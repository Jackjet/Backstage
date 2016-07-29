<?php
namespace Admin\Controller;
use Common\Controller\AuthController;
use Think\Auth;
use Think\Model;

//后台系统
class SysController extends AuthController {

	//用户管理
	public function user_manage() {
		$this -> display();
	}

	//分页显示用户列表
	public function user_list() {
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

		$user = M('user');

		$count = $user -> where($where) -> count();
		$data = $user -> where($where) -> order('id') -> limit($index * $size, $index * $size + $size) -> select();
		$auth = new Auth();
		foreach ($data as $k => $v) {
			$group = $auth -> getGroups($v['id']);
			$data[$k]['group'] = $group[0]['title'];
			$data[$k]['group_id'] = $group[0]['group_id'];
			$data[$k]['login_time'] = date("Y-m-d H:m:s", $data[$k]['login_time']);
			$data[$k]['create_time'] = date("Y-m-d H:m:s", $data[$k]['create_time']);
		}
		$result = array("total" => $count, "data" => $data);
		$this -> ajaxReturn($result, 'JSON');
	}

	//检查账号是否已注册
	public function check_account() {
		$m = M('user');
		$where['account'] = I('account');
		//账号
		$data = $m -> field('id') -> where($where) -> find();
		if (empty($data)) {
			$this -> ajaxReturn(0);
			//不存在
		} else {
			$this -> ajaxReturn(1);
			//存在
		}
	}

	//添加用户
	public function user_add_page() {
		$this -> display();
	}

	public function user_add() {
		if (!empty($_POST)) {
			$returnData['success'] = false;

			$m = M('user');
			$data['account'] = I('account');
			$data['password'] = md5(I('password'));
			$data['create_time'] = time();
			$where['account'] = I('account');
			$result = $m -> where($where) -> find();
			if (!empty($result)) {
				$returnData['info'] = "账户已存在";
			} else {
				//添加用户
				$result['uid'] = $m -> add($data);
				$result['group_id'] = I('group_id');
				if ($result['uid']) {
					$m = M('auth_group_access');
					//分配用户组
					if ($m -> add($result)) {
						$returnData['success'] = true;
					} else {
						$returnData['info'] = "分配用户组失败";
					}
				} else {
					$returnData['info'] = "添加用户失败";
				}
			}
			$this -> ajaxReturn($returnData);
		}
	}

	//修改用户信息
	public function user_edit_page() {
		$this -> display();
	}

	public function user_edit() {
		if (!empty($_POST)) {
			$returnData['success'] = false;
			if (I('id') == 1) {
				$returnData['info'] = "不允许修改超级管理员！";
			} else {
				//修改所属组
				$access = M('auth_group_access');
				$result = $access -> where('uid=' . I('id')) -> find();
				if (empty($result)) {
					$map['uid'] = I('id');
					$map['group_id'] = I('group_id');
					$access -> add($map);
				} else {
					$save['group_id'] = I('group_id');
					$access -> where('uid=' . I('id')) -> save($save);
				}
				$data['id'] = I('id');
				if (!empty(I('password'))) {
					$data['password'] = md5(I('password'));
				}
				if (I('status') >= 0) {
					$data['status'] = I('status');
				}
				$m = M('user');
				$result = $m -> where('id=' . $data['id']) -> save($data);
				if ($result === false) {
					$returnData['info'] = "修改出错了！";
				} else {
					$returnData['success'] = true;
				}
			}
			$this -> ajaxReturn($returnData);
		}
	}

	//删除用户
	public function user_delete() {
		//开启事务  妈蛋。。。事务一直搞不定。。最后发现是数据库引擎的问题。。要改成INNODB
		$model = M('auth_group_access');
		$model2 = M('user');
		$model -> startTrans();
		$flag = true;

		$ids = I('ids');
		foreach ($ids as $id) {
			//用户ID
			if ($id == 1) {
				$returnData['info'] = "不允许删除超级管理员！";
				$flag = false;
				break;
			} else {
				$result = $model -> where('uid=' . $id) -> delete();
				//删除权限表里面给的权限
				$result2 = $model2 -> where('id=' . $id) -> delete();
				if (!$result || !$result2) {
					$flag = false;
					break;
				}
			}
		}
		if ($flag) {
			$model -> commit();
			$returnData['success'] = true;
		} else {
			$model -> rollback();
			$returnData['success'] = false;
			if (!$returnData['info']) {
				$returnData['info'] = "删除出错！";
			}
		}
		$this -> ajaxReturn($returnData);
	}

	//用户组管理
	public function group_manage() {
		$this -> display();
	}

	//查询所有用户组
	public function group_list() {
		$m = M('auth_group');
		$data = $m -> field('id,title,rules,create_time') -> order('id') -> select();
		foreach ($data as $k => $v) {
			$data[$k]['create_time'] = date("Y-m-d H:m:s", $data[$k]['create_time']);
		}
		$this -> ajaxReturn($data);
	}

	//检查用户组名称是否已存在
	public function check_group_title() {
		$m = M('auth_group');
		$where['title'] = I('title');
		$data = $m -> field('id') -> where($where) -> find();
		if (empty($data)) {
			$this -> ajaxReturn(0);
		} else {
			$this -> ajaxReturn(1);
		}
	}

	//添加组
	public function group_add_page() {
		$this -> display();
	}

	public function group_add() {
		if (!empty($_POST)) {
			$returnData['success'] = false;

			$data['rules'] = I('rules');
			if (empty($data['rules'])) {
				$returnData['info'] = "为分配权限！";
			}
			$m = M('auth_group');
			$data['title'] = I('title');
			//			$data['rules'] = implode(',', $data['rules']);
			$data['create_time'] = time();
			if ($m -> add($data)) {
				$returnData['success'] = true;
			} else {
				$returnData['info'] = "添加失败！";
			}
			$this -> ajaxReturn($returnData);
		}
	}

	//编辑组
	public function group_edit_page() {
		$this -> display();
	}

	public function group_edit() {
		if (!empty($_POST)) {
			$returnData['success'] = false;
			$m = M('auth_group');
			$data['id'] = I('id');
			$data['title'] = I('title');
			$data['rules'] = I('rules');
			if ($m -> save($data)) {
				$returnData['success'] = true;
			} else {
				$returnData['info'] = "修改失败";
			}
			$this -> ajaxReturn($returnData);
		}
	}

	//删除组
	public function group_delete() {
		$returnData['success'] = false;
		if (I('id') == 1) {
			$returnData['info'] = "不允许删除超级管理组！";
		} else {
			$where['id'] = I('id');
			$m = M('auth_group');
			if ($m -> where($where) -> delete()) {
				$returnData['success'] = true;
			} else {
				$returnData['info'] = "删除出错了！";
			}
		}
		$this -> ajaxReturn($returnData);
	}

	//分配权限时的树
	public function rule_get() {
		$m = M('auth_rule');
		$field = 'id,name,title';
		$where['pid'] = 0;
		$where['status'] = 1;
		$data = $m -> field($field) -> where($where) -> select();
		$auth = new Auth();
		//操作者自己都没有的权限不显示
		foreach ($data as $k => $v) {
			if (session('aid') != 1 && !$auth -> check($v['name'], session('aid'))) {
				unset($data[$k]);
			} else {
				$data[$k]['children'] = $m -> field($field) -> where('pid=' . $v['id']) -> select();
				foreach ($data[$k]['children'] as $k2 => $v2) {
					if (session('aid') != 1 && !$auth -> check($v2['name'], session('aid'))) {
						unset($data[$k]['children'][$k2]);
					} else {
						$data[$k]['children'][$k2]['children'] = $m -> field($field) -> where('pid=' . $v2['id']) -> select();
						foreach ($data[$k]['children'][$k2]['children'] as $k3 => $v3) {
							if (session('aid') != 1 && !$auth -> check($v3['name'], session('aid'))) {
								unset($data[$k]['children'][$k2]['children'][$k3]);
							}
						}
					}
				}
			}
		}
		$this -> ajaxReturn($data);
	}

	//权限列表
	public function rule_list() {
		$m = M('auth_rule');
		$field = 'id,name,title,create_time,status,sort,pid';
		$data = $m -> field($field) -> select();
		foreach ($data as $k => $v) {
			$data[$k]['create_time'] = date('Y-m-d H:m:s', $data[$k]['create_time']);
		}
		$this -> ajaxReturn($data);
	}

	//修改权限
	public function rule_manage() {
		if (!empty($_POST)) {
			$returnData['success'] = true;

			$arr = I('data');
			$m = M('auth_rule');
			foreach ($arr as $i) {
				if (strcmp($i['_state'], 'modified') == 0) {
					$data['id'] = $i['id'];
					$data['title'] = $i['title'];
					$data['name'] = $i['name'];
					$data['ismenu'] = $i['ismenu'];
					$data['create_time'] = time();
					if (!$m -> save($data)) {
						$returnData['success'] = false;
					}
				} else if (strcmp($i['_state'], 'added') == 0) {
					$data['title'] = $i['title'];
					$data['name'] = $i['name'];
					$data['ismenu'] = $i['ismenu'];
					$data['pid'] = isset($i['pid']) ? $i['pid'] : 0;
					$data['create_time'] = time();
					if (!$m -> add($data)) {
						$returnData['success'] = false;
					}
				} else if (strcmp($i['_state'], 'removed') == 0) {
					if (!$m -> delete($i['id'])) {
						$returnData['success'] = false;
					}
				}
			}
			$this -> ajaxReturn($returnData);
		} else {
			$this -> display();
		}
	}

}
