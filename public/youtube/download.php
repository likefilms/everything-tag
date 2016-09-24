<?php

$config['ThumbnailImageMode']=1;$config['VideoLinkMode']='direct';$config['feature']['browserExtensions']=true;date_default_timezone_set("Asia/Kolkata");$debug=false;function curlGet($URL){$ch=curl_init();$timeout=3;curl_setopt($ch,CURLOPT_URL,$URL);curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);$tmp=curl_exec($ch);curl_close($ch);return $tmp;}function get_location($url){$Uixyullll0=curl_init();curl_setopt($Uixyullll0,CURLOPT_URL,$url);curl_setopt($Uixyullll0,CURLOPT_HEADER,true);curl_setopt($Uixyullll0,CURLOPT_NOBODY,true);curl_setopt($Uixyullll0,CURLOPT_RETURNTRANSFER,true);curl_setopt($Uixyullll0,CURLOPT_TIMEOUT,10);$r=curl_exec($Uixyullll0);foreach(explode("\n",$r) as $header){if(strpos($header,'Location: ')===0){return trim(substr($header,10));}}return '';}function get_size($url){$Uixyullll0=curl_init();curl_setopt($Uixyullll0,CURLOPT_URL,$url);curl_setopt($Uixyullll0,CURLOPT_HEADER,true);curl_setopt($Uixyullll0,CURLOPT_NOBODY,true);curl_setopt($Uixyullll0,CURLOPT_RETURNTRANSFER,true);curl_setopt($Uixyullll0,CURLOPT_TIMEOUT,10);$r=curl_exec($Uixyullll0);foreach(explode("\n",$r) as $header){if(strpos($header,'Content-Length:')===0){return trim(substr($header,16));}}return '';}function get_description($url){$fullpage=curlGet($url);$dom=new DOMDocument();@$dom->loadHTML($fullpage);$xpath=new DOMXPath($dom);$tags=$xpath->query('//div[@class="info-description-body"]');foreach($tags as $tag){$my_description.=(trim($tag->nodeValue));}return utf8_decode($my_description);}ob_start();function clean($uixuixuixY){$uixuixuixY=str_replace(' ','-',$uixuixuixY);return preg_replace('/[^A-Za-z0-9\-]/','',$uixuixuixY);}function formatBytes($bytes,$precision=2){$units=array('B','kB','MB','GB','TB','PB','EB','ZB','YB');$bytes=max($bytes,0);$pow=floor(($bytes?log($bytes):0)/log(1024));$pow=min($pow,count($units)-1);$bytes/=pow(1024,$pow);return round($bytes,$precision).''.$units[$pow];}function is_chrome(){$agent=$_SERVER['HTTP_USER_AGENT'];if(preg_match("/like\sGecko\)\sChrome\//",$agent)){if(!strstr($agent,'Iron'))return true;}return false;}if(isset($_REQUEST['videoid'])){$xYxuioxuiVxi=$_REQUEST['videoid'];if(strpos($xYxuioxuiVxi,"https://youtu.be/")!==false){$xYxuioxuiVxi=str_replace("https://youtu.be/","",$xYxuioxuiVxi);}if($xYxuioxuiVxi==""){exit;}if(strlen($xYxuioxuiVxi)>11){$url=parse_url($xYxuioxuiVxi);$xYxuioxuiVxi=NULL;if(is_array($url)&&count($url)>0&&isset($url['query'])&&!empty($url['query'])){$parts=explode('&',$url['query']);if(is_array($parts)&&count($parts)>0){foreach($parts as $p){$pattern='/^v\=/';if(preg_match($pattern,$p)){$xYxuioxuiVxi=preg_replace($pattern,'',$p);break;}}}if(!$xYxuioxuiVxi){echo '<p>Please Enter Link of Video</p>';exit;}}else{echo '<p>Invalid url</p>';exit;}}}else {echo '<p>Please Enter Link of Video</p>';exit;}?>
<div class="download_cotainer">
<?php
 $oO0oO0qoQo0O='http://www.youtube.com/get_video_info?&video_id='.$xYxuioxuiVxi.'&asv=3&el=detailpage&hl=en_US';
 $oO0oO0qoQo0O=curlGet($oO0oO0qoQo0O);
 $thumbnail_url=$title=$url_encoded_fmt_stream_map=$type=$url='';parse_str($oO0oO0qoQo0O);
 echo '<div class="download_info">';
 echo'<table><tr>';
 echo'<td><img class="downloadThumbnail" src="'.$thumbnail_url.'" border="0" hspace="2" vspace="2"></td>';
 echo'<td><span class="download_title">'.$title.'</span></td>';
 echo'</tr></table>';
 echo '</div>';
 $ttototot=$title;
 $cleanedtitle=clean($title);
 if(isset($url_encoded_fmt_stream_map)){$loOqoQ0qOqpo=explode(',',$url_encoded_fmt_stream_map);
 if($debug){echo '<pre>';print_r($loOqoQ0qOqpo);echo '</pre>';}}else {
 echo '<p>No encoded format stream found.</p>';
 echo '<p>Here is what we got from YouTube:</p>';
 echo $oO0oO0qoQo0O;}
 if(count($loOqoQ0qOqpo)==0){
 	echo '<p>No format stream map found - was the video id correct?</p>';
 	exit;
 }
 $video[]='';
 $i=0;
 $ipbits=$ip=$itag=$sig=$quality='';
 $expire=time();

 foreach($loOqoQ0qOqpo as $format){
 		parse_str($format);
	 	$video[$i]['itag']=$itag;
	 	$video[$i]['quality']=$quality;
	 	$type=explode(';',$type);
	 	$video[$i]['type']=$type[0];
	 	$video[$i]['url']=urldecode($url).'&signature='.$sig;
	 	parse_str(urldecode($url));
	 	$video[$i]['expires']=date("G:i:s T",$expire);
	 	$video[$i]['ipbits']=$ipbits;
	 	$video[$i]['ip']=$ip;
	 	$i++;
 	}

	echo '<input type="hidden" name="uploadYoutube" value="'.$video[0]['url'].'">';
	echo '<input type="hidden" name="typeYoutube" value="'.$video[0]['type'].'">';
?>