<?php header('Content-Type: text/html; charset=UTF-8');?>
<?php
	function writeLog($message){
		$logFileName = "./log.txt";
		$logFile = fopen($logFileName, "a");
		if($logFile){
			session_start();
			fwrite($logFile, "\nTime:\t"
				.date('Y-m-d H:i:s')
				. "\tSessionID:\t".session_id()
				."\tMessage:\t".$message
				."\tURI:\t".$_SERVER['PHP_SELF']		// 현재 페이지
				."\tPrevious:\t".$_SERVER["HTTP_REFERER"] 	// 접속 경로(이전페이지)
			);
			fclose($logFile);
		} else {
			$error = error_get_last();
			echo "[파일열기오류]".$error['message'];
		}
	}
?>

