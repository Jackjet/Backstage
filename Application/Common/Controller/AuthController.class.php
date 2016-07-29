<?php
namespace Common\Controller;
use Think\Controller;
use Think\Auth;
use Think\Model;

//权限认证
class AuthController extends Controller {
	protected function _initialize(){
		//session不存在时，不允许直接访问
		if(!session('aid')){
			$this->error('还没有登录，正在跳转到登录页',U('Public/login'));
		}

		//session存在时，不需要验证的权限
		$not_check = array('Index/index','Index/menu','Index/main','Index/clear_cache',
			'Index/update_pwd','Index/logout','Sys/user_list','Sys/group_list','Sys/rule_list','Sys/rule_get',
			'Sys/user_add_page','Sys/user_edit_page','Sys/group_add_page','Sys/group_edit_page');
		
		//当前操作的请求                 模块名/方法名
		if(session('aid') == 1 || in_array(CONTROLLER_NAME.'/'.ACTION_NAME, $not_check)){
			return true;
		}
		
		//下面代码动态判断权限
		$auth = new Auth();
		if(!$auth->check(CONTROLLER_NAME.'/'.ACTION_NAME,session('aid'))){
			$this->error('没有权限');
		}
	}
}