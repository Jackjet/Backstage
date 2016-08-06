<?php
return array(
	//'配置项'=>'配置值'
	'MODULE_ALLOW_LIST'		=>  array('Home','Admin'),
	'DEFAULT_MODULE'		=>  'Home',
	//默认视图层
	'DEFAULT_V_LAYER'		=>  'View',
	//操作方法后缀
//	'ACTION_SUFFIX'		=>  'Action',
	
	'DB_TYPE'		=>  'mysql',
	'DB_PORT'		=>  '3306',
	'DB_CHARSET'	=>  'utf8',
	'DB_PREFIX'		=>  'db_',
	'DB_DEBUG'		=>  true, 			// 数据库调试模式 开启后可以记录SQL日志
	'DB_HOST'		=>  'qdm160286537.my3w.com',
	'DB_NAME'		=>  'qdm160286537_db',
	'DB_USER'		=>  'qdm160286537',
	'DB_PWD'		=>  'Bjl12345',
	
	'SHOW_PAGE_TRACE'		=>  false,   		// 显示页面Trace信息	
		
	'TMPL_ACTION_SUCCESS'	=>  'Public:dispatch_jump',		//自定义success跳转页面
	'TMPL_ACTION_ERROR'		=>  'Public:dispatch_jump',		//自定义error跳转页面
	
	//自动参数绑定
	'DB_BIND_PARAM'		=>  true,
	
	'TMPL_VAR_IDENTIFY'	=>  'array',
//	'TMPL_FILE_DEPR' => '_',
	
	//加载常量类
	'LOAD_EXT_CONFIG'	=>  'const',
	//加载自定义标签
	'TAGLIB_BUILD_IN'       =>  'Cx,Common\Tag\My',

	//***********************************SESSION设置**********************************
//  'SESSION_OPTIONS'	=>  array(
//  			'name'				=>  'BJYSESSION',  //设置session名
//      		'expire'    		=>  24*3600*15,    //SESSION保存15天
//      		'use_trans_sid'		=>  1,             //跨页传递
//  			'use_only_cookies'	=>  0,             /是否只开启基于cookies的session的会话方式
//  ),
);