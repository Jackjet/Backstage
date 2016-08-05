<?php
function replace_expression($content) {
	preg_match_all('/\[.*?\]/is', $content, $arr);
	if ($arr[0]) {
		$expression = C('expression');
		foreach ($arr[0] as $v) {
			foreach ($expression as $key => $value) {
				if ($v == '[' . $value . ']') {
					$content = str_replace($v, '<img src = "' . __ROOT__ . '/Public/Home/img/expression/' . $key . '.gif"/>', $content);
				}
			}
		}

	}
	return $content;
}

function p($content) {
	print_r($content);
}
