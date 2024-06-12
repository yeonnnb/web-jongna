<?php
	ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

	include("./SQLconstants.php");
	$conn = new mysqli($mySQL_host,$mySQL_id,$mySQL_password,$mySQL_database);
       
	if($conn -> connect_error){	
		die("Connection failed: ".$conn->connect_error);
	}



	if($_SERVER['REQUEST_METHOD']=='POST'){

		$Member_id = $_POST['Member_id'];
		$Member_password = $_POST['Member_password'];
		$Member_name = $_POST['Member_name'];
		$Member_contact = $_POST['Member_contact'];		
		$Member_hashedpassword = password_hash($Member_password, PASSWORD_BCRYPT);
	       	$stmt = $conn->prepare("INSERT INTO Member (Member_id, Member_password, Member_name, Member_contact, Member_hashedpassword) VALUES (?, ?, ?, ?, ?)");
											                
		if($stmt === false){
			die("Prepare failed: ". htmlspecialchars($conn->error));
		}

		$stmt->bind_param("sssss", $Member_id, $Member_password, $Member_name, $Member_contact, $Member_hashedpassword);
												                
		if($stmt->execute()){
			echo "New record created successfully";
                } else {
			echo "Error: ". htmlspecialchars($stmt->error);
		}
		$stmt->close();
        }
	        $conn->close();
?>

<!DOCTYPE html>
<html>
<body>

        <h2>회원가입</h2>
        <form method = "post" action="register_test.php">
                아이디:<br>
                <input type="text" name="Member_id" required>
                <br>
                <br>
                비밀번호:<br>
                <input type="password" name="Member_password" required>
                <br>
                <br>

		        이름:<br>
		        <input type="text" name="Member_name" required>
		        <br>
                <br>
		        연락처:<br>
		        <input type="text" name="Member_contact" required>
		        <br><br><br>
                <input type="submit" value="회원가입">
        </form>
</body>
</html>
