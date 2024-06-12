<!-- 가게 사진, 가게명, 평점, 주소, 전화번호, 운영시간, 휴게시간 가져오기 -->
<?php
	// 이전 페이지에서 전달 받은 가게 번호로 가게 정보 가져옴
	/* $message =  $_POST['message'];
	$message = ( ( ( $message == null ) || ( $message == "" ) || ( strncmp( $message, " * ", 3 ) == 0 ) ) ? "_%" : $message );
	*/
	include("./SQLconstants.php");
	$conn = mysqli_connect($mySQL_host,$mySQL_id,$mySQL_password,$mySQL_database) or die ("Can't access DB");

	if($conn->connect_error){
		die("Connection failed: ". $conn->connect_error);
	}
	// 가게 번호 확인

	if(isset($_POST['store_number'])){
		$message = $_POST['store_number'];
	}else{
		echo "Store_number 값이 전달되지 않았습니다.";
	}
	
        include("./log.php");
        writeLog($message."가게상세페이지 출력");
	//가게 번호에 맞는 가게 정보, 리뷰 저장
	$store_query = "select * from Store where Store_number = '".$message."'";
	$review_query = "select * from Review where Review_store_number = '".$message."'";
	$store_resultSet = mysqli_query( $conn, $store_query );
	$review_resultSet = mysqli_query( $conn, $review_query );

	$store_result = mysqli_fetch_array( $store_resultSet );
	
	$r = $review_resultSet->num_rows;
	$storeRate = 0;
	$storeRateInfo = 0;

	if($store_resultSet->num_rows>0)
	{
		echo "\n\t\t\t\t<img src = '".$store_result['Store_image']."' width= '700px' height= '350px'>";
		echo "\n\t\t\t\t<div class = 'info_box'><div style='display: flex; justify-content: space-between;'><div class='big_text' style='font-size:25;'>".$store_result['Store_name']."</div>";
		if($r>0)
		{
			while($review_result = mysqli_fetch_array( $review_resultSet ))
			{
				$storeRate += $review_result['Review_score'];
			}
			$storeRateInfo = $storeRate / $r;
			
			echo "\n\t\t\t\t<div class='big_text' style='font-size:25;'>".sprintf('%0.1f', $storeRateInfo);
		}
		else
		{
			echo "\n\t\t\t\t<div class='big_text'>0.0";
		}
		echo "\n\t\t\t\t<img src = './rate_icon.png' height='25' width='25'></div></div>";
		echo "\n\t\t\t\t<a style='font-weight: bold'>#".$store_result['Store_category']."</a>";
		echo "\n\t\t\t\t<br><div style='display: flex; justify-content: space-between;'><div><br>주소: ".$store_result['Store_address'];
		echo "\n\t\t\t\t<br>전화번호: ".$store_result['Store_contact'];
		echo "\n\t\t\t\t<br>운영 시간: ".$store_result['Store_time'];
		echo "\n\t\t\t\t<br>휴게 시간: ".$store_result['Store_breaktime'];
		//"</div><div><button class='jjim_btn' onclick='jjim_pop()'>찜하기</button></div></div></div>" 찜하기 버튼 코드
		
	}
	else{
		echo "\n\t\t\t\t해당하는 레코드가 없습니다.";
	}

	$conn->close();
?>


