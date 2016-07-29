/** ========获取缓存纳税人信息方法集合== */
function setCacheObject(key, data) {
	var str = mini.encode(data);
	SUI.store.set(key, str);
}

function getCacheObject(key) {
	return mini.decode(SUI.store.get(key));
}

function getUser() {
	var userData = mini.decode(SUI.store.get("user"));
	return userData;
}

/**从Session中获取登录的用户信息，并设置到页面缓存中
 * 
 * @return {flag} 
 */
function getUserSession() {
	var flag = false;
	$.ajax( {
		url : "/common/common_getUser.do",
		async : false,
		type : "POST",
		success : function(data) {
			var result = mini.decode(data);
			if (result.success) {
				setCacheObject("user", result.value);
				flag = true;
			} else {
				//flag = false;
		/**测试代码，尽限测试阶段使用*/
			flag = true;
			var userVO = {};
			userVO.fzgs_Dm = "001080";
			userVO.ssbmName = "总公司";
			userVO.userid = "adminTest";
			userVO.username = "管理员";
			setCacheObject("user", userVO);
		/*****************************/
	}
},
error : function(data) {
	mini.alert("获取登录用户信息出现异常。", "提示信息");
}
	});
	return flag;
}

function initUserForm() {
	var userForm = {};
	if (!getUserSession()) {
		//统一跳转至错误提示页面
		window.location.href = "../../pages/error/mesage.html";
	} else {
		userVO = getUser();
	}
	userForm.zzrDq = userVO.fzgs_Dm;
	userForm.zzrDqmc = userVO.ssbmName;
	userForm.zzrDm = userVO.userid;
	userForm.zzrMc = userVO.username;
	return userForm;
}

function onCloseClick(e) {
	if (window.CloseOwnerWindow)
		return window.CloseOwnerWindow();
	else
		window.close();
}

function onCharacter(e){
	if (e.isValid) {
        if (isCharacter(e.value) == false) {
            e.errorText = "存在非法字符，请输入正确的名称";
            e.isValid = false;
        }
    }
}
function isCharacter(v){
	var chiness = new RegExp("[~！!@#￥%……&*()——{}？。，》《':]+$");
//	var englishNumber = new RegExp("^[0-9a-zA-Z（）\_]+$");
	if (chiness.test(v) ) return false;
    return true;
}
function gdsxq(e) {
	if(e== '1'){return '国税版';}
	else if(e == '2'){return '地税版';}
	else if(e == '3'){return '独立产品';}
	else if(e == '4'){return '专版';}
}