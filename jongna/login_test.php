<?php	

	if(session_status() == PHP_SESSION_NONE){
                session_start();
        }

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
        include("./SQLconstants.php");
        $conn = new mysqli($mySQL_host,$mySQL_id,$mySQL_password,$mySQL_database);
        if($conn->connect_error)
	{
                die("Connection failed: ". $conn->connect_error);
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $username = $_POST['username']??'';
                $password = $_POST['password']??'';

                $stmt = $conn->prepare("select * from Member where Member_id = ?");
                if($stmt === false){
                        die("Prepare failed: ". $conn->error);
                }
                if(!$stmt->bind_param("s", $username)){
                        die("Bind param failed: ". htmlspecialchars($stmt->error));
                }
                if(!$stmt->execute()){
                        die("Execute failed: ". htmlspecialchars($stmt->error));
                }

                $result = $stmt->get_result();

                if($result->num_rows>0){
                        $row = $result->fetch_assoc();
                        $hashed_password = $row['Member_hashedpassword'];

			if(password_verify($password, $hashed_password)){
				$_SESSTION['username'] = $username; 
				$_SESSION['username'] = $row['Member_id'];
			        $_SESSION['name'] = $row['Member_name'];
			        $_SESSION['contact'] = $row['Member_contact'];	
				header("Location: index.php");
                                exit();
                        } else {
                                echo "Invalid password.";
                        }
                }else{
                        echo "No user found.";
                }
                $stmt->close();
        }
        $conn->close();
?>
<!DOCTYPE html>
<html>
<body>
        <h2>login</h2>
        <form method="post" action="login_test.php">
                Username:<br>
                <input type="text" name="username" required>
                <br>
                Password:<br>
                <input type="password" name="password" required>
                <br><br>
                <input type="submit" value="Login">
        </form>
</body>
</html>
