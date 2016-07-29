<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=">
		<title>后台管理系统</title>
		<script src="/Backstage/Public/Common/boot.js"></script>
		<script>
			function onSaveClick() {
				var pwdForm = new mini.Form("pwdForm");
				pwdForm.validate();
				if(!pwdForm.isValid()) {
					return;
				}
				var pwdData = pwdForm.getData();
				var messageid = mini.loading("操作中...");
				$.post("/Backstage/index.php/Admin/Index/update_pwd.html?_winid=w7701&amp;_t=478791", pwdData, function(data, status) {
					mini.hideMessageBox(messageid);
					if(status && data.success) {
						mini.loading("请重新登录...", "修改成功");
						setTimeout(function() {
							parent.window.location = "<?php echo U('Public/login');?>";
						}, 100);
					} else if(status) {
						mini.alert(data.info);
					} else {
						mini.alert("网络异常");
					}
				}, "json");
			}

			function pwdRepeatValidation(e) {
				if(e.isValid) {
					var pwdForm = new mini.Form("pwdForm");
					var pwdData = pwdForm.getData();
					if(pwdData.new_pwd2 != pwdData.new_pwd) {
						e.errorText = "密码输入不一致";
						e.isValid = false;
					}
				}
			}
		</script>
	</head>

	<body>
		<div align="center">
			<form id="pwdForm">
				<table border="0" cellpadding="1" cellspacing="2">
					<tr>
						<td class="mini-item" style="width:80px;">旧密码：</td>
						<td class="mini-content">
							<input class="mini-textbox" name="old_pwd" required="true" />
						</td>
					</tr>
					<tr>
						<td class="mini-item">新密码：</td>
						<td class="mini-content">
							<input class="mini-password" name="new_pwd" required="true" />
						</td>
					</tr>
					<tr>
						<td class="mini-item">重复新密码：</td>
						<td class="mini-content">
							<input class="mini-password" name="new_pwd2" onvalidation="pwdRepeatValidation" onenter="onSaveClick()" />
						</td>
					</tr>
				</table>
			</form>
		</div>
		<div align="center" style="margin-top: 20px;">
			<a class="mini-button" style="margin-right: 20px;" iconCls="icon-save" onclick="onSaveClick()">保存</a>
			<a class="mini-button" style="margin-left: 20px;" iconCls="icon-cancel" onclick="onCloseClick()">取消</a>
		</div>

	</body>

</html>