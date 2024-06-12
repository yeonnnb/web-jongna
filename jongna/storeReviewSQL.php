<!-- 가게 사진, 가게명, 평점, 주소, 전화번호, 운영시간, 휴게시간 가져오기 -->
<?php 
	// 이전 페이지에서 전달 받은 가게 번호로 가게 정보 가져옴
	// $message =  $_POST['message'];
	//$message = ( ( ( $message == null ) || ( $message == "" ) || ( strncmp( $message, " * ", 3 ) == 0 ) ) ? "_%" : $message );

	include("./SQLconstants.php");
	$conn = mysqli_connect($mySQL_host,$mySQL_id,$mySQL_password,$mySQL_database) or die ("Can't access DB");

	if($conn->connect_error){
		die("Connection failed: ". $conn->connect_error);
	}
	//넘겨받은 번호 확인
	if(isset($_POST['store_number'])){
                $message = $_POST['store_number'];
        }else{
                echo "Store_number 값이 전달되지 않았습니다.";
        }

	$query = "select * from Review where Review_store_number = '".$message."'";
	$resultSet = mysqli_query( $conn, $query );

	if($resultSet->num_rows > 0)
	{
		while($result = mysqli_fetch_array( $resultSet ))
		{
			echo "\n\t\t\t<div class = 'store_review'><div style='display: flex; justify-content: space-between;'>";
	
			//회원 이름 알아오기
			$MN_query = "select * from Member where Member_id = '".$result['Review_member_id']."'";
			$MN_resultSet = mysqli_query( $conn, $MN_query );
			$MN_result = mysqli_fetch_array( $MN_resultSet );
	
			echo "\n\t\t\t<div class='big_text'>".$MN_result['Member_name']."</div>";
			
			echo "\n\t\t\t<div class='big_text'>".sprintf('%0.1f', $result['Review_score']);

			echo"\n\t\t\t\t<img src = './rate_icon.png' height='20' width='20'></div></div>";
			echo "\n\t\t\t\t<div class='review_box'>".$result['Review_text']."</div>";
			//echo "\n<br><img src = '".$result['Store_image']."' height='300' width='300'>";
			echo "\n\t\t\t\t<div class='date_text'>".$result['Review_day']."</div>";
			echo "\n\t\t\t</div>";
			echo "\n\t\t\t<div style='height:10px;'></div>";
		}
	}
	else{
		echo "\n\t\t\t해당하는 레코드가 없습니다.";
	}

	$conn->close();
?>


