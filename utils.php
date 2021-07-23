<?php
	function debugLog($msg){
		print "<script>console.log('$msg')</script>";
		print_r(debug_backtrace());
	}
	
	function formatLog($msg, $in_class=true){
	    $backtrace = debug_backtrace();
		$file = trim($backtrace[0]["file"]);
		$line = trim($backtrace[0]["line"]);
		$out = trim($backtrace[0]["args"][0]);
		
		if($in_class){
		    $class = trim($backtrace[1]["class"]);
		    $method = trim($backtrace[1]["function"]);
		    
		    print "<script>console.log('[$class] $method | " . $msg . " ($line)')</script>";
		}else{
		    $method = trim($backtrace[0]["function"]);
		    print "<script>console.log('[] $method | " . $msg . " ($line)')</script>";
		}
	}
?>