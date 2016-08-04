<?php
/*
*打开txt文档进行匹配
*$patternarray为规则数组
*$filename为文件路径
*/
function preg_match_content($patternarray,$filename){
	$results=array();
	$contetn=file_get_contents($filename);
	for($i=0;$i<count($patternarray);$i++){
		preg_match_all($patternarray[$i],$contetn,$result);
		array_push($results,$result);
	}
	return $results;
}
?>