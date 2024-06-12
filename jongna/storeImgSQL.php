<!-- 가게 사진, 가게명, 평점, 주소, 전화번호, 운영시간, 휴게시간 가져오기 -->
<?php
        // 이전 페이지에서 전달 받은 가게 번호로 가게 정보 가져옴
        //$message =  $_POST['message'];
        //$message = ( ( ( $message == null ) || ( $message == "" ) || ( strncmp( $message, " * ", 3 ) == 0 ) ) ? "_%" : $message );

        include("./SQLconstants.php");
        $conn = mysqli_connect($mySQL_host,$mySQL_id,$mySQL_password,$mySQL_database) or die ("Can't access DB");

        if($conn->connect_error){
                die("Connection failed: ". $conn->connect_error);
        }
        //전달받은 번호 확인
        if(isset($_POST['store_number'])){
                $message = $_POST['store_number'];
        }else{
                echo "Store_number 값이 전달되지 않았습니다.";
        }
	//가게의 사진 3장 저장
        $query = "select * from StoreImage where StoreImage_id = '".$message."' limit 3";
        $resultSet = mysqli_query( $conn, $query );

        if($resultSet->num_rows > 0)
	{
		//사진 3번 출력
        	while($result = mysqli_fetch_array( $resultSet )) {
			echo "<img class='store_image_list' src = '".$result['StoreImage_image_URL']."'>";	
		}     
        }
        else{
                echo "해당하는 레코드가 없습니다.";
        }

	$conn->close();
?>
