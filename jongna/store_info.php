<?php include('./Error_D_back.php');?>
<?php header('Content-Type: text/html; charset=UTF-8');?>
<html>
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
			function openPopup() {
			        var storeNumber = "<?php echo $storeNumber; ?>";
				window.open('storeImgSQL_more.php?store_number=' + storeNumber, 'popup', 'width=900,height=900');
			}
			function Move(url) {
    var storeNumber = "<?php echo $storeNumber; ?>";
//폼으로 가게번호를 전달하기 위한 함수 생성

    // Create a form dynamically
    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", url);

    // Create an input element for storeNumber
    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", "store_number");
    hiddenField.setAttribute("value", storeNumber);

    // Append the hidden field to the form
    form.appendChild(hiddenField);

    // Append the form to the body and submit it
    document.body.appendChild(form);
    form.submit();
}
				
		</script>
		<meta charset="utf-8">
		<title>jongna</title>
		<link rel="stylesheet" href="./ex_style.css">
		<link rel="stylesheet" href="./store_info.css">
	</head>
	<body>
		<!--버튼으로 받아온 상점번호 SQL파일로 값 넘기기 -->
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

		        sendPostRequest("storeInfoSQL.php", storeNumber).then(response => {
		            document.getElementById("store_info").innerHTML = response;
		        }).catch(error => console.error('Error:', error));
	
		        sendPostRequest("storeImgSQL.php", storeNumber).then(response => {
		            document.getElementById("store_img").innerHTML = response;
		        }).catch(error => console.error('Error:', error));

		        sendPostRequest("storeReviewSQL.php", storeNumber).then(response => {
		            document.getElementById("store_review").innerHTML = response;
			}).catch(error => console.error('Error:', error));

			/*sendPostRequest("storeJjimSQL.php", storeNumber).then(response => {
                            document.querySelector(".jjimPopUp").innerHTML = response;
			}).catch(error => console.error('Error:', error));*/
		    });
        	</script>
		<div class="header">
			<div class="search_zone">
			    <form name="formm" method="post" action="./storeList.php" style="display:inline">
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
						echo '<INPUT class="jjim_btn" style="font-size: 16px;" TYPE="button" value="로그인" onClick="javascript:move(\'./jongna_login_page.php\');">';
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
		<script language="javascript">
			const containers = document.querySelectorAll('.category-container');

			containers.forEach(container => {
				const categoryList = container.querySelector('.category');
				
				container.addEventListener('mouseenter', function(){
					categoryList.style.display = 'block';	
				});
				container.addEventListener('mouseleave', function(){
					categoryList.style.display = 'none';
				});
				
			});
			
		</script>
		<div class="jjim_text" style="padding-top:10px;"> <!--list_name 입력 받고 유저 아이디로 찜목록 DB에 추가-->
			<div class="close_btn" onclick="jle()">X</div>
			찜 목록 이름을 입력해주세요.<br><br>
			<form style="display:flex;" name="jjim_form" method="POST" action="">
				<input style="padding:5px;" type="text" name="jjim_name">&nbsp;
				<input id="JFbtn" type="submit" name="submit">
			</form>
		</div>
		<div class="jjimPopUp"> <!--찜하기 버튼 눌렀을 때 찜목록 보여주기--></div>		
		<div class="text">
			<br>
			<div id="store_info">
			</div>
			<hr>
			<div style="padding-top: 25px">
				<a class="big_text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;가게 이미지</a>
                                <br>
				<div style="display: flex">
					<div id="store_img"></div>
				</div>
				<div class="R_btn" style="text-align: right; padding-right: 40px;"  onclick="openPopup()">더 많은 이미지 보기</div>
			</div>
			<br><hr><br>
			<div class="R">
				<div class="big_text" style="display: flex;">리뷰</div>
				<div class="R_btn" onclick="Move('./review_page.php')">리뷰 작성</div>
			</div>
			<div id="store_review"style="padding: 25px">
				<br><br>
				<br>
			</div>
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
	<script language="javascript">
			const pop = document.querySelector(".jjimPopUp");
			const j = document.querySelector(".jjim_text");

                        
                        function jjim_pop()
                        {  
                                pop.classList.add('on');
                        }
         
                        function jjim_erase()
                        {
                                pop.classList.remove('on');
                        }
    
                        function Jjim_add(list_name)
                        {
				//list_name, 유저 아이디로 찜목록 DB에 추가하고 팝업 지우기
				pop.classList.remove('on');
                        }
    
                        function create_jjim_list()
                        {
				pop.classList.remove('on');
				j.classList.add('on');
			}

			function jle()
			{
				j.classList.remove('on');
			}
                </script>
</html>

