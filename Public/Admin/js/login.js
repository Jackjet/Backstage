$(function() {
	mini.parse();
})

function onResetClick() {
	var loginForm = new mini.Form("loginform");
	loginForm.reset();
}

function onLoginClick() {
	var loginForm = new mini.Form("loginform");
	loginForm.validate();
	if(!loginForm.isValid()) {
		return;
	}
	var check_code = mini.get("check_code");
	if(check_code.getValue() == 0) {
		mini.showTips({
			content: "验证码不正确",
			state: "warning",
			timeout: 3000
		});
		return;
	}
	var loginData = loginForm.getData();
	var messageid = mini.loading("登录中 ...");
	$.post(loginUrl, loginData, function(data, status) {
		mini.hideMessageBox(messageid);
		if(status && data.success) {
			mini.loading("正在进入系统...", "登录成功");
			setTimeout(function() {
				window.location = indexUrl;
			}, 100);
		} else if(status) {
			mini.alert(data.info);
		} else {
			mini.alert("网络异常！");
		}
	});
}

function check_code() {
	var code = mini.get("code");
	var check_code = mini.get("check_code");
	if(code.getValue().length == 4 && check_code.getValue() == 0) {
		$.get(codeUrl, {
			code: code.getValue()
		}, function(data) {
			if(data == 1) {
				check_code.setValue(1);
				mini.showTips({
					content: "验证码正确",
					state: "warning",
					timeout: 3000
				});
			} else {
				check_code.setValue(0);
				mini.showTips({
					content: "验证码错误",
					state: "warning",
					timeout: 3000
				});
				return;
			}
		});
	}
}