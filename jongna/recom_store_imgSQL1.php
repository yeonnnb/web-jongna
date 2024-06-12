<?php
	include("./SQLconstants.php");
	$conn = mysqli_connect($mySQL_host,$mySQL_id,$mySQL_password,$mySQL_database) or die ("Can't access DB");

	if($conn->connect_error){
		die("Connection failed: ". $conn->connect_error);
	}

	$sql = "SELECT *
			FROM (
			    SELECT *, COUNT(*) OVER (PARTITION BY Store_review_point) AS cnt
			    FROM Store
			) AS subquery
			ORDER BY Store_review_point DESC, 
		        CASE WHEN cnt > 5 THEN Store_number ELSE 0 END
			LIMIT 4;"; //평점이 높은 4개의 가게 선택
	$result = $conn -> query($sql);

	if($result->num_rows>0){
		while($row = $result->fetch_assoc()){
			$store_number = $row["Store_number"];
			$store_img = $row["Store_image"];
			echo "<div class='re_restaurant'>";
			echo "<form id = 'store_number_load' action='store_info.php' method='post'>";//가게상세정보페이지로 번호 전달 form
			echo "<input type='hidden' name='store_number' value='$store_number'>";//가게상세정보페이지로 번호 전달
			echo "<button type='submit' name='submit'>";//가게상세정보페이지로 번호 전달을 위한 버튼
			echo "<img src='$store_img' alt='이미지' height='150' width='200' style='cursor: pointer; border: 2px solid #EA2626;'>";//버튼에 이미지 씌우기
			echo "</button>";
			echo "</form>";

			echo "<div class='re_info'>";
			echo "<div style='display: flex; justify-content: space-between;'>&nbsp;".$row["Store_name"]."</div>";
			echo "<div style='display: flex;'>".$row["Store_review_point"]."&nbsp;<img src = './rate_icon.png' height='20' width='20'></div>";
			echo "</div>";
			echo "</div>";
		}
	}
        else{
	        echo "해당하는 레코드가 없습니다.";
        }
        $conn->close();
?>
