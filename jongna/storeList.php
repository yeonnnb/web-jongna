<?php //include('./Error_D_back.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
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
		<link rel="stylesheet" href="./storeList_style.css">
	</head>

	<body>
		<div class="header">
			<div class="search_zone">
				<form name = "formm" method = "post" style="display:inline">
					<input type="text" name="message" size="35" style = "padding: 8px; font-size: 16px; border: 3px solid #EA2626;">
				</form>
				<img src="./search_icon.png" style="display:inline; cursor: pointer;" height='39' width='39' onclick="submitFormWithCategory('검색');">
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

		<div class="sect">
			<div class="storelist">
				<!-- 현재 카테고리와 정렬 -->
				<div class="ect">
					<div class="current_category" id="current_category">
					<?php
						$cate = isset($_GET['category']) ? $_GET['categoty'] : null;	
					    if ($cate=="검색") {
					    	echo '검색어 : ' . $_POST['message'];
					    }else {
					        echo '현재 카테고리: ' . $_GET['category'];
					    }
					    ?>
   					</div>
					<button class="sort_container">평점 순</button>
					<!-- <div class="sort_container">리뷰 많은 순</div> -->
				<!--<div class="add_store">내 맛집 추가하기 >></div> -->
				</div>	

				<!-- 가게 목록 -->
				<ul class="storelist_ul">
    				<li class="storelist_li">
     				   	<div class="post-container">
          					<img class="post-image"></img>
          					<p class="post-name"></p>
          					<p class="post-address"></p>
        				</div>
     				</li>
    			</ul>
  				<div class="pagination-container">
      				<div class="prev-button"><</div>
      				<div class="number-button-wrapper"><span class="number-button"></span></div>
      				<div class="next-button">></div>
    			</div>
    			<?php include("./storeListSQL.php")?>
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
</html>

<?php/*
	include("./log.php");
	writeLog("접속했습니다");
 */?>
