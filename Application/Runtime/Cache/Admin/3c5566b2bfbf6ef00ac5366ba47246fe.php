<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=">
		<title>登录</title>
		<link href="/Backstage/Public/Admin/css/login.css" rel="stylesheet" >
		<script src="/Backstage/Public/Common/boot.js" ></script>
		<script>
			indexUrl = '<?php echo U("Index/index",'','');?>';
			loginUrl = '<?php echo U("Public/login",'','');?>';
			codeUrl = '<?php echo U("Public/check_code",'','');?>';
		</script>
		<script src="/Backstage/Public/Admin/js/login.js"></script>
	</head>

	<body>

		<div class="main">
			<div class="login">
				<form name="loginform" id="loginform">
					<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
						<tr>
							<td class="mini-item">验证码：</td>
							<td class="mini-content"><input class="mini-textbox" name="code" id="code" required="true" emptyText="请输入验证码" maxlength="4" onValuechanged="check_code()" />
								<input class="mini-hidden" id="check_code" value="0"></td>
							<td class="mini-item" width="50%" rowspan="3">看不清，点击图片刷新
								<div id="code_img">
									<img style="border:1px solid #D4E7F6; cursor:pointer;" title="点我刷新哦！" src="<?php echo U('Public/verify');?>" onclick="javascript:this.src=this.src+'?time='+Math.random()" />
								</div>
							</td>
						</tr>
						<tr>
							<td class="mini-item">用户名：</td>
							<td class="mini-content"><input class="mini-textbox" name="account" id="account" required="true" value="user1" emptyText="请输入用户名" maxlength="20" onenter="onLoginClick()"/></td>
						</tr>
						<tr>
							<td class="mini-item">密&nbsp;&nbsp;&nbsp;码：</td>
							<td class="mini-content"><input class="mini-password" name="password" id="password" required="true" value="user1" emptyText="请输入密码" onenter="onLoginClick()"/></td>
						</tr>
					</table>
				</form>
				<div style="text-align: center;">
					<span class="btn_login">
						<a class="mini-button"onclick="onLoginClick()"　style="margin-right: 45px;">登&nbsp;&nbsp;录</a> </span>
					<span class="btn_login">
						<a class="mini-button" onclick="onResetClick()" style="margin-left: 45px;">重&nbsp;&nbsp;置</a> </span>
				</div>
			</div>
			<div class="login_bottom">Copyright © bjl@CQU</div>
		</div>

	</body>

</html>