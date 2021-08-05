<?php
	function debugLog($msg){
		print '<script>console.log("'. $msg . '")</script>';
	}
	
	function formatLog($msg, $in_class=true){
	    $backtrace = debug_backtrace();
		$file = trim($backtrace[0]["file"]);
		$line = trim($backtrace[0]["line"]);
		$out = trim($backtrace[0]["args"][0]);
		$msg = str_replace(array("\r", "\n", "\r\n", "\n\r", "\t"), '', $msg);
		
		if($in_class){
		    $class = trim($backtrace[1]["class"]);
		    $method = trim($backtrace[1]["function"]);
		    
		    echo '<script>console.log("' . "[$class] $method | " . $msg . "|($line)" . '")</script>';
		}else{
		    $method = trim($backtrace[0]["function"]);
		    echo '<script>console.log("' . "[] $method | " . $msg . "|($line)" . '")</script>';
		}
	}
	
	function defaultValue($value, $default=null){
		if(isset($value)){
			return $value;
		}else{
			return $default;
		}
	}
	
	function defaultMapValue($map, $key, $default=null){
		if(array_key_exists($key, $map)){
			return $map[$key];
		}else{
			return $default;
		}
	}
?>