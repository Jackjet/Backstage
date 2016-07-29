<?php
namespace Admin\Model;
use Think\Model;
//如果不希望依赖字段缓存或者想提高性能，也可以在模型类里面手动定义数据表字段的名称，可以避免IO加载的效率开销
class UserModel extends Model {
	//表默认就对应为user
	protected $tableName = 'user'; 
	protected $fields = array('id', 'username', 'password', 'createtime');
	//默认就是id
    protected $pk = 'id';
	//自动验证
	protected $_validate = array(
//  	array('verify','require','验证码必须！'), //默认情况下用正则进行验证
    	array('username','','帐号名称已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一
//  	array('value',array(1,2,3),'值的范围不正确！',2,'in'), // 当值不为空的时候判断是否在一个范围内
//  	array('repassword','password','确认密码不正确',0,'confirm'), // 验证确认密码是否和密码一致
//  	array('password','checkPwd','密码格式不正确',0,'function'), // 自定义函数验证密码格式
   );
   //自动完成
   protected $_auto = array ( 
//       array('status','1'),  // 新增的时候把status字段设置为1
         array('password','md5',3,'function') , // 对password字段在新增和编辑的时候使md5函数处理
//       array('name','getName',3,'callback'), // 对name字段在新增和编辑的时候回调getName方法
         array('createtime','time',2,'function'), // 对update_time字段在更新的时候写入当前时间戳
     );
}