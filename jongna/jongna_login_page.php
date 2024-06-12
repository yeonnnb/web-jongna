<?php

	if(session_status() == PHP_SESSION_NONE)
	{
		session_start();
        }

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	//display error 확인
	//SQL 접속
        include("./SQLconstants.php");
	$conn = new mysqli($mySQL_host,$mySQL_id,$mySQL_password,$mySQL_database);
	
        if($conn->connect_error)
        {
                die("Connection failed: ". $conn->connect_error);
	}
	//로그인 확인
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
                } //post 값 오류 점검 

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
                                		echo "비밀번호가 맞지 않습니다.";
                        			}
                				} else {
                        			echo "올바른 아이디가 아닙니다.";
                				}	
				$stmt->close();
				
        	}
	
		$conn->close();
?>
<?php// include("./Error_D_back.php");?>
<!DOCTYPE html>
<HTML>
    <head>
	
        <meta charset="UFT-8">
	<title>jongna</title>
	
	<link rel = "stylesheet" href="./cssdesign_duck.css">
	<link rel="stylesheet" href="./ex_style.css">
    </head>
	<div class="header">
    	<div class="search_zone">
        <form name="form" method="post" action="./storeList.php" style="display: inline;">
        <input type ='text' name="message" size="35" style="padding: 8px; font-size: 16px; border: 3px solid #EA2626;">
        </form>
        <img type ="submit" src="./search_icon.png" style="display: inline; cursor: pointer;" height="39" width="39" onclick="submitFormWithCategory('검색');">
    </div>
<body>
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
	function move(url){
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
	<img style="cursor: pointer;" src="./jongna_logo.png" height="70" width="175" onclick="javascript:move('./index.php');">
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
            <h1>Login</h1>  
            <br><!--로그인창-->
            <form method="post" action="jongna_login_page.php">
                <br>
                <input type="text" placeholder="아이디" name="username" required style="margin-left: 35px; height:50px; width: 372px;">
                <br>
                <br>
                <input type="password" placeholder="비밀번호" name="password" required style="margin-left: 35px; height:50px; width: 372px;">
                <br><br>
		<input type="submit" value="로그인" style="background-color:#EA2626;
							   width:372px;
							   height:50px;
							   margin-left:35px;
							   margin-bottom:20px;
							   font-size:25px;
         						   color:white;
							   border-color:#EA2626;"
							   >

            </form>
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

        <div class="login_singup">
	<p>
	<!--회원가입버튼-->
        <h4>회원이 아니라면?</h4>
        <div class="login_singup_button" onclick="javascript:move('./jongna_register_page.php')" >
        <input value="회원가입" type="submit" name="SignUp_button" style=" width: 100px; 
                                                                        height: 40px; 
                                                                        font-size: 20px; 
                                                                        font-weight: bold; 
                                                                        padding-right: 20px;
                                                                        padding-bottom: 5px; 
                                                                        background-color: white; 
                                                                        color: black; 
                                                                        border: 0px solid black; 
									cursor: pointer;">
        </div> 
            
	</p>		</div>
			        
        </p>

        </div>

        <div class="footer">	
            <div>
                
                <div class=" footer-left-box">
             
                </div>
                <div class="footer-address-box" style="margin-left: 150px;">
                    <ul>
                        <li>
                            <p>상명대학교 게임학과</p>
                            <p>서울시 종로구 홍지문 2길 20 상명대학교(03016)    </p>
                        </li>
                    </ul>
                </div>
            </div>


            <!--<div class="footer-message">합리적인 분들과 좋은 컨텐츠가 지속될 수 있는 선순환 시스템을 지향합니다</div>
            <div class="footer-contact">컨택: dream@fun-coding.org</div>
            <div class="footer-copyright">Copyrigh 2020 All ⓒ rights reserved</div> -->
		</div>		
	</body>
</HTML>
