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
        writeLog($message."리뷰작성페이지 출력");

	$query = "select * from Store where Store_number = '".$message."'";
	$resultSet = mysqli_query( $conn, $query );

	$result = mysqli_fetch_array( $resultSet );
	if($resultSet->num_rows>0)
	{
		echo "가게 이름:".$result['Store_name'];
	}
	else{
		echo "\n\t\t\t\t해당하는 레코드가 없습니다.";
	}

	$conn->close();
?>


