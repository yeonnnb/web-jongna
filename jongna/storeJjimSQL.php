<!-- 찜목록 가져오기 -->
<?php
	
	include("./SQLconstants.php");
	$conn = mysqli_connect($mySQL_host,$mySQL_id,$mySQL_password,$mySQL_database) or die ("Can't access DB");

	if($conn->connect_error){
		die("Connection failed: ". $conn->connect_error);
	}
	/*
	// 유저 번호 가져오기. $messsage는 유저 아이디
	if(isset($_POST['Member_id'])){
		$message = $_POST['Member_id'];
	}else{
		echo "Member_id 값이 전달되지 않았습니다.";
	}
	*/
	
    	include("./log.php");
    	writeLog("찜하기 팝업 출력");

    	$message = "BGRYX";
	$query = "select DISTINCT List_name from Favorite_list where List_member_id = '".$message."'";
	$resultSet = mysqli_query( $conn, $query );

	echo "\n<div class = 'close_btn' onclick='jjim_erase()'>X</div>";

	/*if(유저번호가 없다면)
	{
		echo "\n로그인이 필요한 기능입니다.";
	}*/
	if($resultSet->num_rows>0)
	{
		while($result = mysqli_fetch_array( $resultSet ))
		{
			echo "\n<div class = 'jjim_box' onclick='Jjim_add(".$result['List_name'].")'>";
			echo "\n<div class='check_box'>추가</div>&nbsp;&nbsp;&nbsp;<div class='text'>".$result['List_name']."</div></div>";
		}
		
		echo "\n<hr style='margin-top:10px;margin-bottom:10px;'><div class='text' style='cursor:pointer;' onclick='create_jjim_list()'>+ 새 찜 목록 만들고 추가하기</div>";
		
	}
	else{ //로그인 돼있지만 만든 찜 목록이 없을 때
		echo "\n<br><div class='text' style='cursor:pointer;' onclick='create_jjim_list()'>+ 새 찜 목록 만들고 추가하기</div>";
	}

	$conn->close();
?>



