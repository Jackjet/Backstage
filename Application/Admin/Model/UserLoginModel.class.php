<?php
namespace Admin\Model;
use Think\Model;
//如果不希望依赖字段缓存或者想提高性能，也可以在模型类里面手动定义数据表字段的名称，可以避免IO加载的效率开销
class UserLoginModel extends Model {
	protected $fields = array('id', 'userid', 'logintime', 'loginip');
	//默认就是id
    protected $pk = 'id';
}