<?php
	include("./SQLconstants.php");
	$conn = mysqli_connect($mySQL_host,$mySQL_id,$mySQL_password,$mySQL_database) or die ("Can't access DB");

	if($conn->connect_error){
		die("Connection failed: ". $conn->connect_error);
	}
	//광고중인 식당의 이미지 출력
	$sql = "select * from Store where Store_number = 'S_000002'";
	$result = $conn->query($sql);

	if($result->num_rows>0){
		$row = $result->fetch_assoc();
		$store_img = $row["Store_image"];
		echo "<img src='$store_img' alt='이미지' width= '750px' height= '300px'>";
	}
	else{
		echo "해당하는 레코드가 없습니다.";
	}

	$conn->close();
?>

