<?php
session_start();
//만약 미로그인 상태일 경우 로그인창으로 강제 이동

if (!isset($_SESSION['username'])) {
    header("Location: jongna_login_page.php");
    exit();
}
?>
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

		<!-- 리뷰작성페이지 내용 코딩 시작 -->
		<div class = "text" style="width: 600px;">
			<br><br>
			<a class="big_text" style="font-size:25px;">&nbsp;&nbsp;&nbsp;리뷰 작성</a>
			<hr>
			<br><br>
			<div id = "reviewsql" style = " align-items: center;">
				<?php //include("./reviewSQL.php")?>
			</div>
			<br><br>
			<div style="display: flex; align-items: center; padding-left: 140px; padding-right:140px;">
				평점
				<div id="rateImg">
					<img class="rateIcon" src="./rate_icon_full.png" onclick="rate(1)">
					<img class="rateIcon" src="./rate_icon_full.png" onclick="rate(2)">
					<img class="rateIcon" src="./rate_icon_full.png" onclick="rate(3)">
					<img class="rateIcon" src="./rate_icon_full.png" onclick="rate(4)">
					<img class="rateIcon" src="./rate_icon_full.png" onclick="rate(5)">
				</div>
				<div id="rateNum">5.0</div>
				<script>
				function rate(score) {
					const rateIcons = document.querySelectorAll('.rateIcon');
					document.getElementById("rateNum").innerText = score + ".0";

        // 클릭된 이미지 이전의 이미지는 꽉찬 아이콘으로 변경
			        for (let i = 0; i < score; i++) {
			            rateIcons[i].src = "./rate_icon_full.png";
			        }

        // 클릭된 이미지 이후의 이미지는 빈 아이콘으로 변경
			        for (let i = score; i < rateIcons.length; i++) {
			            rateIcons[i].src = "./rate_icon.png";
			        }

        // 여기서는 클릭된 점수를 어딘가에 저장하거나 활용할 수 있습니다.
        // 이 예시에서는 단순히 콘솔에 출력합니다.
			     	   console.log("Clicked score:", score);
				}	
				</script>
			</div>
			<br><br>
			<div style="display: flex; padding-left: 120px;">
				리뷰 &nbsp;
				<textarea id="RT" style="font-size: 16; line-height: 1.4; resize: none; border: 3px solid #EA2626; padding: 5px;" cols="30" rows="8"></textarea>
			</div>
			<br>
			<div id="test" style="text-align: center">
				<input class="jjim_btn" style="padding: 7px; padding-left:100px; padding-right:100px;" type="submit" name="com" value="완료" onclick="sendData()">
			</div>
<script>
//SQL로 데이터를 전송하기 위한 post 함수
function sendData() {
    const rateNum = document.getElementById("rateNum").innerText;
    const reviewContent = document.getElementById("RT").value;
    const storeNumber = <?php echo json_encode($storeNumber); ?>;

    const formData = new FormData();
    formData.append('rateNum', rateNum);
    formData.append('reviewContent', reviewContent);
    formData.append('storeNumber', storeNumber);

    fetch('./reviewAddDBSQL.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.ok) {
            alert('리뷰 작성에 성공하였습니다.');
	    //작성 후 이전 store_info.php로 이동
	    window.history.back();
        } else {
            throw new Error('오류가 발생했습니다. 다시 시도해주세요.');
        }
    })
    .catch(error => {
        alert(error.message);
    });
}

	</script>
			<br><br><br>
		</div>

		
		<div class="footer">	
		</div>
	</body>
</html>

<?php/*
	include("./log.php");
	writeLog("접속했습니다");
 */?>



