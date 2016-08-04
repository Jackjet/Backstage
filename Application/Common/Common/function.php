<?php
	function replace_phiz($content){
		preg_match_all('/\[.*?\]/is',$content,$arr);
		if($arr[0]){
			$phiz = C('expression');
			foreach($arr[0] as $v){
				foreach($phiz as $key => $value){
					if($v == '[' . $value . ']'){
						$content = str_replace($v,'<img src = "' . __PUBLIC__ . '/Home/img/phiz/' . $key . '.gif"/>',$content);
					}	
				}
			}
			
		}
		return $content;
	}

	function p($content){
		print_r($content);
	}