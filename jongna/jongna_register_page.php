<?php
	ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	//include("./Error_D_back.php");

        include("./SQLconstants.php");
        $conn = new mysqli($mySQL_host,$mySQL_id,$mySQL_password,$mySQL_database);

        if($conn -> connect_error){
                die("Connection failed: ".$conn->connect_error);
        }
	//post받은 회원정보 저장
        if($_SERVER['REQUEST_METHOD']=='POST'){
                $Member_id = $_POST['Member_id'];
                $Member_password = $_POST['Member_password'];
		$Member_name = $_POST['Member_name'];
		$Member_contact = $_POST['Member_contact'];
		$hashed_password = password_hash($Member_password, PASSWORD_BCRYPT);
		
		$stmt = $conn->prepare("INSERT INTO Member (Member_id, Member_password, Member_name, Member_contact, Member_hashedpassword) VALUES (?, ?, ?, ?, ?)");

		if($stmt === false){	
                        die("Prepare failed: ". htmlspecialchars($conn->error));
                }

                $stmt->bind_param("sssss", $Member_id, $Member_password, $Member_name, $Member_contact, $hashed_password);
                
		if($stmt->execute()){
			//echo "New record created successfully";
			header("Location: jongna_login_page.php");
			exit();
                } else {
                        echo "Error: ". htmlspecialchars($stmt->error);
                }
                $stmt->close();
        }
        $conn->close();
?>
<?php// include ("./Error_D_back.php");?>
<!DOCTYPE html>
<HTML>
    <body>
    <head>
        <script language="javascript"></script>
        <meta charset="UFT-8">
        <title>jongna</title>
	<link rel = "stylesheet" href="./cssdesign_duck.css">
	<link rel = "stylesheet" href="./ex_style.css">
    </head>

	<div class="header">
		<div class="search_zone">
			<form name="form" method="post" action="./storeList.php" style="display: inline;">
			<input type ='text' name="message" size="35" style="padding: 8px; font-size: 16px; border: 3px solid #EA2626;">
			</form>
			<img type ="submit" src="./search_icon.png" style="display: inline; cursor: pointer;" height="39" width="39" onclick="submitFormWithCategory('검색');">
		</div>
		<script>
			function submitFormWithCategory(category){
				var form = document.formm;
				form.action = "./storeList.php?category="+encodeURIComponent(category);
				form.submit();
			}
			 document.addEventListener('keypress', function(e) {
                                    if (e.key === 'Enter') {
                                        e.preventDefault(); // 기본 엔터 키 동작 방지
                                        submitFormWithCategory('검색');
                                    }
                                });

			function move(url) {
				window.location.href = url;
			}
		</script>   				
		
		<div class="top_menu" style="align-items:center; display: flex;">   			   
			<?php
                                        if (!isset($_SESSION['username'])) {
                                                echo '<INPUT class="jjim_btn" style="font-size: 16px;" TYPE="button" value="로그인" onClick="javascript:move(\'./jongna_login_page.php\');">';
                                        } else {
                                                echo '<INPUT class="jjim_btn" style="font-size: 16px;" TYPE="button" value="마이페이지" onClick="javascript:move(\'./mypage.php\');">';
                                                echo '<INPUT class="jjim_btn" style="font-size: 16px; margin-left: 10px;" TYPE="button" value="로그아웃" onClick="javascript:move(\'./logout_test.php\');">';
                                        }
                                ?>
			
		</div>
	
		<div class="logo">       	
			<div style="height: 50px; width: 50%;"></div>
			<img style="cursor: pointer;" src="./jongna_logo.png" height="70" width="175" onclick="javascript:move('./index.php')">
		</div>
	</div>
	
	<div class="meddle_menu">
		<div style="height: 50px; width: 350px;"></div>
			<!--<div class="middle_menu_btn" onclick=""여기부터 작업시작해야함-->
			<div class="middle_menu_btn" onclick="window.location.href='./storeList.php?category=한식';"> 한식</div>
			<div class="middle_menu_btn" onclick="window.location.href='./storeList.php?category=양식';"> 양식</div>
			<div class="middle_menu_btn" onclick="window.location.href='./storeList.php?category=중식';"> 중식</div>
			<div class="middle_menu_btn" onclick="window.location.href='./storeList.php?category=일식';"> 일식</div> 
			<div class="middle_menu_btn" onclick="window.location.href='./storeList.php?category=카페';"> 카페</div> 
			<div class="middle_menu_btn" onclick="window.location.href='./storeList.php?category=주점';"> 주점</div>
			<div style="height: 50px; width: 350px;"></div>
	</div>

	<div class="login">
		<br>
		<h1>Register</h1>  
		<br>
		<form method = "post" action="jongna_register_page.php">
		
			<input type="text" placeholder="아이디" name="Member_id" required style="margin-left: 35px; height: 20px; ">
			<br>
			<br>
		
			<input type="password" placeholder="비밀번호" name="Member_password" required style="margin-left: 35px; height: 20px;">
			<br>
			<br>
			
			<input type="text" placeholder="이름" name="Member_name" required style="margin-left: 35px; height: 20px;">
			<br>
			<br>
			
			<input type="text" placeholder="연락처" name="Member_contact" required style="margin-left: 35px; height: 20px;">
			<br><br>
			<input type="submit" value="회원가입" style="background-color:#EA2626; width:372px; height:50px; margin-left: 35px; margin-bottom: 5px; font-size:25px; color: white; border-color: #EA2626;">

		<!--<div class ='login_id'>
		<input type ='text' name="id" placeholder="아이디"> 
		</div>
			
		<div class="login_password">
		<input type="text" name="PASSWORD" placeholder="비밀번호">
		</div>
		
		<div class="login_button">
		<input value="로그인" type="submit" name="Login_button" style="font-weight: bold; font-size: 20px ;">
		</div>-->
		
		<div class ="login_line">
		<hr style="border: solid 2px black; width: 420px;">   
		</div>
	</div>
	<div class="footer">	
	<div>
		<div class=" footer-left-box">
			
		</div>
		
		<div class="footer-address-box" style="margin-left: 150px; ">
		<ul>
			<li>
			<p>상명대학교 게임학과</p>
			<p>서울시 종로구 홍지문 2길 20 상명대학교(03016)    </p>
			</li>
		</ul>
		</div>
		</div>
	</div>

		<!--<div class="footer-message">합리적인 분들과 좋은 컨텐츠가 지속될 수 있는 선순환 시스템을 지향합니다</div>
		<div class="footer-contact">컨택: dream@fun-coding.org</div>
		<div class="footer-copyright">Copyrigh 2020 All ⓒ rights reserved</div> -->
	</div>		
	</body>
</HTML>
