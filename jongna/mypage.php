<?php include('./Error_D_back.php');?>
<?php header('Content-Type: text/html; charset=UFT-8');?> 
<HTML>
    <HEAD>
        <script language="javascript">
            function showMessage(message)
            {
                if((message != null) && (message != "") && (message.substring(0,3) == " * ")){
                    alert(message);
                }
            }
            function move(url){
                document.formm.action = url;
                document.formm.submit();
            }
        </script>
        <meta charset="utf-8">
        <title>jongna</title>
        <link rel="stylesheet" href="./ex_style.css">
        <link rel="stylesheet" href="./store_info.css">
        <link rel="stylesheet" href="./mypage_style.css">
    </head>

    <body>      
<script>
    document.addEventListener("DOMContentLoaded", function() {
    var storeNumber = "<?php echo $storeNumber; ?>";
    function sendPostRequest(url, storeNumber) {
        return fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ 'store_number': storeNumber })
        }).then(response => response.text());
    } 
    sendPostRequest("reviewSQL.php", storeNumber).then(response => {
        document.getElementById("reviewsql").innerHTML = response;
    }).catch(error => console.error('Error:', error));
    });
</script>
        <div class="header">
            <div class="search_zone">
                <form name = "formm" method = "post" style="display:inline">
                    <input type="text" NAME="message" size="35" style = "padding: 8px; font-size: 16px; border: 3px solid #EA2626;">
                </form>
                <img type="submit" src="./search_icon.png" style="display:inline; cursor: pointer;" height='39' width='39' onclick="submitFormWithCategory('검색');">
            </div>

            <script>
                function submitFormWithCategory(category) {
                    var form = document.formm;
                    form.action = "./storeList.php?category=" + encodeURIComponent(category);
                    form.submit();
                }

                document.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault(); // 기본 엔터 키 동작 방지
                        submitFormWithCategory('검색');
                    }
                });
            </script>

            <div class="top_menu" style="align-items:center; display: flex;">
                                <?php
                                        if (!isset($_SESSION['username'])) {
                                                echo '<INPUT class="jjim_btn" style="font-size: 16px;" TYPE="button" value="로그인" onClick="javascript:move(\'./login_test.php\');">';
                                        } else {
                                                echo '<INPUT class="jjim_btn" style="font-size: 16px;" TYPE="button" value="마이페이지" onClick="javascript:move(\'./mypage.php\');">';
                                                echo '<INPUT class="jjim_btn" style="font-size: 16px; margin-left: 10px;" TYPE="button" value="로그아웃" onClick="javascript:move(\'./logout_test.php\');">';
                                        }
                                ?>
                        </div>
            <div class="logo">
                <div style="height: 50px; width: 50%;"></div>
                <img style=" cursor: pointer;" src="./jongna_logo.png" height="70" width="175" onClick="javascript:move('./index.php');">
            </div>
        </div>
        <div class = "meddle_menu">
                <div style="height: 50px; width: 350px;"></div>
                <div class="middle_menu_btn" onclick="window.location.href='./storeList.php?category=한식';"> 한식</div>
                <div class="middle_menu_btn" onclick="window.location.href='./storeList.php?category=양식';"> 양식</div>
                <div class="middle_menu_btn" onclick="window.location.href='./storeList.php?category=중식';"> 중식</div>
                <div class="middle_menu_btn" onclick="window.location.href='./storeList.php?category=일식';"> 일식</div>
                <div class="middle_menu_btn" onclick="window.location.href='./storeList.php?category=카페';"> 카페</div>
                                <div class="middle_menu_btn" onclick="window.location.href='./storeList.php?category=주점';"> 주점</div>
                                <div style="height: 50px; width: 350px;"></div>

        </div>      

        <!-- 마이페이지 회원정보 출력 -->
        <div class = "text" style="width: 600px;">
            <?php include("./mypageSQL2.php"); 
            $member_name = trim($member_name);
            $member_id = trim($member_id);
            $member_password = trim($member_password);
            session_start();
  	    $user_name = $_SESSION['username'];
            echo "<script>console.log('$user_name');</script>";
            ?>
            <br><br>
            <a class="big_text" style="font-size:25px;">&nbsp;&nbsp;&nbsp;마이페이지</a>
            <hr>
            <br><br>
            <div style="text-align: center">
                <h2>회원 정보</h2>
            </div>
            <br><br>
            <div class="members">
                <p class="member_info"> 
                    <label>닉네임</label>
                    <textarea class="member" id="nickname" style="line-height: 1.4" cols="33" rows="1" readonly><?php echo htmlspecialchars($member_name); ?></textarea>
                    <br> 
                </p>
                <p class="member_info">
                    <label>아이디</label>
                    <textarea class="member" id="userid" style="line-height: 1.4" cols="33" rows="1" readonly><?php echo htmlspecialchars($member_id); ?></textarea>
                    <br>
                </p>
                <p class="member_info">
                    <label>비밀번호</label>
                    <textarea class="member" id="password" style="line-height: 1.4" cols="33" rows="1" readonly><?php echo htmlspecialchars($member_password); ?></textarea>
                </p>
            </div>

	    <br>
	<!--회원정보 수정 버튼-->
            <div style="text-align: center">
                <a class="jjim_btn" style="padding: 7px; padding-left:30px; padding-right:30px;" href="mypageModify_name.php">닉네임 수정</a>
                <a class="jjim_btn" style="padding: 7px; padding-left:30px; padding-right:30px;" href="mypageModify_id.php">아이디 수정</a>
                <a class="jjim_btn" style="padding: 7px; padding-left:30px; padding-right:30px;" href="mypageModify_password.php">비밀번호 수정</a>
            </div>
            <br><br>
        </div>

        <!-- 마이페이지 나의 찜 목록 -->

        <div class = "text" style="width: 600px;">
            <hr>
            <br><br>
            <div style="text-align: center">
                <h2>나의 찜 목록</h2>
            </div>
            <br><br>
            <div>

                
            </div>
            <!-- <form name='deleteStoreForm_$store_number' method='post' action='delete_store.php' class='delete-store-form'>
            	<input type='hidden' name='member_id' value='$member_id'>
            	<input type='hidden' name='list_name' value='$list_name'>
            	<input type='hidden' name='store_number' value='$store_number'>
            	<button type='button' class='delete-store-btn' onclick='confirmStoreDelete(\"$store_name\", \"$store_number\");'>가게 삭제</button>
            </form> -->
            <?php //include("./mypageSQL1.php"); ?>
            </div>

      <div class="footer">  
        <div class="footer-address-box" style="margin-left: 150px;">
                    <ul>
                        <li>
                            <p>상명대학교 게임학과</p>
                            <p>서울시 종로구 홍지문 2길 20 상명대학교(03016)    </p>
                        </li>
                    </ul>
		</div>
		</div>

    </body>
</html>

<?php/*
    include("./log.php");
    writeLog("접속했습니다");
 */?>



