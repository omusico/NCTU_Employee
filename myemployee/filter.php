<?php
//去除特殊字元
function filterEvil($str) {
	if($str===null || $str=='')
		return '';
	$str = preg_replace('/"|\'/', '', $str);
	$str = preg_replace('/[\/]/', '', $str);     
	$str = preg_replace('/[\\\]/', '', $str);	
	$str = preg_replace('/(&nbsp;)/','',$str);
	clean_xss($str);
	return $str;
}

function filterDateTime($str){
	return preg_replace('/[\\\'a-zA-z]/', '',$str);
}

function replaceSpaceChar($str){
	return preg_replace( "/\s/", "" , $str );
}

function filterEvilBig5($str){
	$str=mb_convert_encoding(filterEvil(mb_convert_encoding($str,"utf-8","big5")),"big5","utf-8");
	return $str;
}

function convertBig5($str, $filter=true){
	if($str===null || $str=='')
		return '';
	$str = mb_convert_encoding($str,"big5","utf-8");
	if($filter){
		$str = trim($str);
		$str = filterEvilBig5($str);
	}
	return $str;
}
	
function convert($str){
	if($str===null || $str=='')
		return '';
	$str = mb_convert_encoding($str,"utf-8","big5");
	$str = trim($str);
	return $str;
}

function convertN($str){
	if($str===null || $str=='')
		return '';
	$str = mb_convert_encoding($str,"UTF-8","auto");
	$str = trim($str);
	return $str;
}


function clean_xss(&$string, $low = False)
{
	if (! is_array ( $string ))
	{
		$string = trim ( $string );
		$string = strip_tags ( $string );
		$string = htmlspecialchars ( $string );
		if ($low)
		{
			return True;
		}
		$string = str_replace ( array ('"', "\\", "'", "/", "..", "../", "./", "//" ), '', $string );
		$no = '/%0[0-8bcef]/';
		$string = preg_replace ( $no, '', $string );
		$no = '/%1[0-9a-f]/';
		$string = preg_replace ( $no, '', $string );
		$no = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';
		$string = preg_replace ( $no, '', $string );
		return True;
	}
	$keys = array_keys ( $string );
	foreach ( $keys as $key )
	{
		clean_xss ( $string [$key] );
	}
}

function tranToBig5($string_utf8){
	$string_big5 = @iconv('utf-8', 'big5', $string_utf8);
	if(mb_strlen($string_utf8,'UTF-8')!==mb_strlen($string_big5,'big5')){
		
		$length = mb_strlen($string_utf8,'UTF-8');
		$string_big5 = '';
		for($i=0;$i<$length;$i++){
			$sub_utf8 = mb_substr($string_utf8, $i, 1, 'UTF-8');
			$sub_big5 = @iconv('utf-8', 'big5', $sub_utf8);
			
			if(mb_strlen($sub_big5,"big5")==0){
				$string_big5.=  "&#".base_convert(bin2hex(iconv("utf-8", "ucs-2", $sub_utf8)), 16, 10).";";
			}
			else{
				$string_big5.=$sub_big5;
			}
		}
	}
	else{
		$string_big5;
	}
	
	return $string_big5;
}


function tranRareStr($string_utf8){
	$string_return = '';
	$string_big5 = @iconv('UTF-8', 'BIG5', $string_utf8);
	if(mb_strlen($string_utf8,'UTF-8')!==mb_strlen($string_big5,'BIG5')){
		$length = mb_strlen($string_utf8,'UTF-8');
		$string_big5 = '';
		for($i=0;$i<$length;$i++){
			$sub_utf8 = mb_substr($string_utf8, $i, 1, 'UTF-8');
			$sub_big5 = @iconv('UTF-8', 'BIG5', $sub_utf8);
			
			if(mb_strlen($sub_big5,"BIG5")==0){
				$string_return.=  "&#".base_convert(bin2hex(iconv("UTF-8", "ucs-2", $sub_utf8)), 16, 10).";";
			}
			else{
				$string_return.=$sub_utf8;
			}
		}
	}
	else{
		$string_return = $string_utf8;
	}
	return $string_return;
}

?>