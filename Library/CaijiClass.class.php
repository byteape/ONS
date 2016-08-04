<?php
/*
 * 原创作者：王国梁 QQ:1132083961
 * 采集类
 * */
class CaijiClass{
	public $domain;//采集的主域名
    public $url;//采集链接
	public $content;//采集的内容
	public $errors=array();//输出错误内容
	public $charset;
    public function __construct($url="",$charset="",$domain="") {
		$this->url=$url;
		$this->charset=$charset;
		$mainurl=is_array($url)?$domain:$url;
		if(!$mainurl){
			array_push($this->errors,array('domainError'=>'主域名必须被指定！'));
		}else{
			preg_match('#(https?\://[^/]+)(/.*)?#',$mainurl, $matches);
	        $this->domain=$matches[1]?$matches[1]:'';	
		}
        //查看环境是否支持相关函数
		$functionArray=array('curl_init','iconv');
		for($i=0;$i<count($functionArray);$i++){
			if(!function_exists($functionArray[$i])){
				array_push($this->errors,array($functionArray[$i]=>$functionArray[$i].'函数不支持！'));
			}
		}	
	}
	/*
	@采集下载文件
	@$filename 文件存储路径
	*/
	public function getImg($url,$filename) {
		if(is_dir(basename($filename))) {
			array_push($this->errors,array('getImg'=>'文件路径错误'));
			return false;
		}
		//去除URL连接上面可能的引号
		$url = preg_replace( '/(?:^[\'"]+|[\'"\/]+$)/', '', $url );
		$hander = curl_init();
		$fp = fopen($filename,'wb');
		$this->curl_downLoad($fp,$url);//下载文件
		fclose($fp);
		return  true;
    }
	/*
	@清除空格、回车换行符
	@$str 处理的字符串
	@$is_saveHtml 是否保留html标签
	*/
	public function removetag($str,$space,$is_saveHtml=false){  
        $str = trim($str);
		if($is_saveHtml){
			$str = @strip_tags($str,"");//strip_tags 删除HTML元素
		}
        $str = @ereg_replace("\t","",$str);
		if(!$space){
			$str = @ereg_replace("\r\n","",$str);
            $str = @ereg_replace("\r","",$str);
            $str = @ereg_replace("\n","",$str);
		}
        $str = @ereg_replace(" ","",$str);
        $str = @ereg_replace("&nbsp;","",$str);
        return trim($str);
    }
	public function curl_downLoad($file_hander=false,$url=""){
		$thisurl=$url?$url:$this->url;
		$hander = curl_init();// 初始化
		curl_setopt($hander, CURLOPT_URL, $thisurl);//设置CURL采集地址
		curl_setopt($hander, CURLOPT_RETURNTRANSFER, 1);//将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。 
		curl_setopt($hander, CURLOPT_HEADER, 0);//是否取得头信息
		//伪造ip和来路域名
		$ip=rand(20,255).".".rand(20,255).".".rand(20,255).".".rand(20,255);
		$keywords="";$keywordslength=rand(20,90);
		for($m=0;$m<$keywordslength;$m++){
		  $keywords.=substr("abcdefghijklmnopqrstuvwxyz",rand(0,25),1);
	    }
		$fromurl="http://www.baidu.com/s?key=".$keywords;
		curl_setopt($hander, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.$ip, 'CLIENT-IP:'.$ip));  //构造IP  
        curl_setopt($hander, CURLOPT_REFERER, $fromurl); 
		if($file_hander){curl_setopt($hander,CURLOPT_FILE,$file_hander);curl_setopt($hander,CURLOPT_FOLLOWLOCATION,1);}
		curl_setopt($hander,CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)");
		curl_setopt($hander,CURLOPT_TIMEOUT,30);		
		$post_content =curl_exec($hander);//执行CURL程序并获取HTML文档内容
		return $post_content;
	}
    /*
	@获取网页内容
	@$file_hander 下载类型文件名句柄
	@$is_line 是否把获取的内容转为一行
	@$is_saveHtml 是否保留html标签
	@$post_content 为得到的返回值，如果是多进程处理，则是数组，否则为字符串
	*/
	public function getAllContent($is_line=true,$space=false,$file_hander=false,$is_saveHtml=false){
		if(!is_array($this->url)){
			//不开启多线程处理
			$post_content=$this->curl_downLoad($file_hander);
			while(!$post_content){
				$post_content=$this->curl_downLoad($file_hander);
			}
			/*$encode = mb_detect_encoding($post_content,mb_detect_order(),false);//自动识别网页编码,需要开启extension=php_mbstring.dll*/
			if($this->charset!="UTF-8")
				$post_content=iconv($this->charset, "UTF-8//IGNORE",$post_content);//如果不是utf-8,则转码
			if($is_line)
				$post_content=$this->removetag($post_content,$space,$is_saveHtml);//是否去除空格等多余标签	
			$this->content=$post_content;
		}else{
			//支持多线程处理
			 $urlarray=$this->url;
			 $count = count($urlarray); 
			 $main= curl_multi_init();// 创建批处理cURL句柄 
			 $results = array();  
			 $errors  = array();  
			 $info = array();
			for($c = 0; $c < $count; $c++){    
				$handles[$c] = curl_init($urlarray[$c]);    
				curl_setopt($handles[$c], CURLOPT_URL, $urlarray[$c]);    
				curl_setopt($handles[$c], CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($handles[$c], CURLOPT_TIMEOUT,100);   //只需要设置一个秒的数量就可以  
                curl_setopt($handles[$c], CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)"); 				
				curl_multi_add_handle($main, $handles[$c]);  
			}  
			$running = null; 
			do{    
			   $content=curl_multi_exec($main, $running);
			}while($running > 0);   
			for($c = 0; $c < $count; $c++){ 
				$content=curl_multi_getcontent($handles[$c]);
				/*$encode = mb_detect_encoding($content,mb_detect_order(),false);//自动识别网页编码*/
				if($this->charset!="UTF-8")
				     $content=iconv($this->charset, "UTF-8//IGNORE",$content);//如果不是utf-8,则转码
				$results[]=$content;
				$errors[]  = curl_error($handles[$c]);    
				$cnfo[]    = curl_getinfo($handles[$c]); 
                curl_close($handles[$c]);				
				curl_multi_remove_handle($main, $handles[$c]);  
			}
			curl_multi_close($main);
            $post_content['results']=$results;$post_content['errors']=$errors;$post_content['info']=$info;	
            $this->content=$post_content;			
		}
	    return $post_content;			
	}
	/*
	@匹配数组
	@$patternArray 规则数组 $patternArray=array([0]=>'//')
	*/
	public function  pregMatch($patternArray){
		if(!$this->content) $this->content=$this->getAllContent();//如果直接调用匹配先获取内容
		for($i=0;$i<count($patternArray);$i++){
			preg_match_all($patternArray[$i],$this->content,$result[$i]);
		}
		$result=$result?$result:'没有内容被匹配到';
		return $result;
	}
}