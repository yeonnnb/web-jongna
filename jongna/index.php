<?php include('./Error_D_back.php');?>
<?php header('Content-Type: text/html; charset=UFT-8');?>
<HTML>
	<HEAD>
		<script language="javascript">
			//url 이동 함수
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
		<!--css파일 불러오기-->
		<link rel="stylesheet" href="./ex_style.css">
	</head>
	<body>
		<div class="header">
			<!-- <div class="search_zone">
				<form name = "formm" method = "post" style="display:inline">
					<input type="text" NAME="message" size="35" style = "padding: 8px; font-size: 16px; border: 3px solid #EA2626;">
				</form>
				<img src="./search_icon.png" style="display:inline; cursor: pointer;" height='39' width='39' onclick="">
			</div> -->
			<!--검색창-->
			<div class="search_zone">
			    <form name="formm" method="post" action="./storeList.php" style="display:inline">
			        <input type="text" NAME="message" size="35" style = "padding: 8px; font-size: 16px; border: 3px solid #EA2626;">
			        
			    </form>
			    <img type="submit" src="./search_icon.png" style="display:inline; cursor: pointer;" height='39' width='39' onclick="submitFormWithCategory('검색');">
			</div>
			<script>
			//키워드 검색 함수 + enter 검색 허용
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
			<!--로그인 여부에 따른 버튼 출력-->
                                <?php
					if (!isset($_SESSION['username'])) {
						
                                                echo '<INPUT class="jjim_btn" style="font-size: 16px;" TYPE="button" value="로그인" onClick="javascript:move(\'./jongna_login_page.php\');">';
					} else {
						echo '<INPUT class="jjim_btn" style="font-size: 16px;" TYPE="button" value="마이페이지" onClick="javascript:move(\'./mypage.php\');">';
                                                echo '<INPUT class="jjim_btn" style="font-size: 16px; margin-left: 10px;" TYPE="button" value="로그아웃" onClick="javascript:move(\'./logout_test.php\');">';
                                        }
                                ?>      
                        </div>
			<!--로고 버튼-->
			<div class="logo">
				<div style="height: 50px; width: 50%;"></div>
				<img style=" cursor: pointer;" src="./jongna_logo.png" height="70" width="175" onClick="javascript:move('./index.php');">
			</div>
		</div>
		<!--카테고리 별 버튼-->
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
		<br>
		<div class = "menu">
			<div class="banner">
				<?php include("./bannerSQL.php")?>
			</div>
		</div>
		<br>
		<!--추천 리스트 출력-->
		<div class="recommendation">
			<a class="big_text">&nbsp;&nbsp;&nbsp;추천맛집</a>
			<br>
			<div class="recommendation_container">
				<?php include("./recom_store_imgSQL1.php")?>
			</div>
		</div>
		
		<!--<div class ="list_menu">
			<p>추천리스트</p>
			<div class="re_list">
			</div>
		</div>-->
		<br><br><br>
		<!--footer출력-->
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

<?php
	include("./log.php");
	writeLog("접속했습니다");
 ?>
