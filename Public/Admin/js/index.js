$(function() {
	mini.parse();
})

//退出登录
function onLogoutClick() {
	mini.confirm('你确定要退出吗？', '确认', function(action) {
		if(action == "ok") {
			window.location.href = logoutUrl;
		} else {
			return;
		}
	});
}

//修改密码
function onUpdatePwdClick() {
	mini.open({
		url: "./update_pwd.html",
		title: "修改密码",
		width: 350,
		height: 200,
	});
}

//清除缓存
function onClearCacheClick() {
	mini.open({
		url: "./clear_cache.html",
		title: "清除缓存",
		width: 420,
		height: 120,
	});
}

//定时刷新页面时间
function reloadTime() {
	var theDate = mini.get("theDate");
//	theDate.setValue(new Date().toLocaleString());
	var myDate = new Date();
	theDate.setValue(myDate.getFullYear()+'-'+(myDate.getMonth()+1)+'-'+myDate.getDate()+' '+
					myDate.getHours()+':'+myDate.getMinutes()+':'+myDate.getSeconds());
}
window.setInterval("reloadTime()", 1000);

//菜单对应显示tab页
function showTab(node) {
	var tabs = mini.get("mainTabs");

	var id = "tab$" + node.id;
	var tab = tabs.getTab(id);
	if(!tab) {
		tab = {};
		tab._nodeid = node.id;
		tab.name = id;
		tab.title = node.title;
		tab.showCloseButton = true;
		tab.url = baseUrl + '/' + node.name;

		tabs.addTab(tab);
	}
	tabs.activeTab(tab);
}

//菜单节点选择
function onNodeSelect(e) {
	var node = e.node;
	var isLeaf = e.isLeaf;

	if(isLeaf) {
		showTab(node);
	}
}

//关闭一个tab页
function onTabsActiveChanged(e) {
	var tree = mini.get("menuTree");
	var tabs = e.sender;
	var tab = tabs.getActiveTab();
	if(tab && tab._nodeid) {

		var node = tree.getNode(tab._nodeid);
		if(node && !tree.isSelectedNode(node)) {
			tree.selectNode(node);
		}
	}
}

//重新加载tab页
function onReloadClick() {
	var tabs = mini.get("mainTabs");
	var tab = tabs.getActiveTab();
	tabs.loadTab(tab.url, tab);
}