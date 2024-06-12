<?php
  include("./SQLconstants.php");
  $conn = mysqli_connect($mySQL_host,$mySQL_id,$mySQL_password,$mySQL_database) or die ("Can't access DB");

  if($conn->connect_error){
    die("Connection failed: ". $conn->connect_error);
  }
?>

<script>
    // 삭제 여부를 확인하는 함수
    function confirmDelete(listName) {
        // confirm() 함수를 사용하여 사용자에게 확인 팝업을 띄웁니다.
        var result = confirm("정말로 '" + listName + "' 찜 목록을 삭제하시겠습니까?");

        // 사용자가 확인을 클릭한 경우에만 삭제 요청을 서버로 전송합니다.
        if (result) {
            document.forms["deleteForm_" + listName].submit();
        }
    }
</script>

<?php
	session_start();
    $user_name = $_SESSION['username'];
    echo "<script>console.log('$user_name');</script>";
    
    // 배열 길이 구하기
    $sql = "SELECT COUNT(DISTINCT List_name) AS count FROM Favorite_list WHERE LIst_member_id = '$user_name';";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $data_length = $row['count'];
    echo "<script>console.log('$data_length');</script>";

    // 찜목록 가게 리스트 이름 배열
    $sql = "SELECT DISTINCT List_name FROM Favorite_list WHERE LIst_member_id = '$user_name';";
    $result = $conn->query($sql);

    $list_names = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $list_name = $row['List_name'];
            $store_info = [];

            // 각 List_name에 해당하는 List_store_number를 가져오는 쿼리
            $store_sql = "SELECT LIst_store_number FROM Favorite_list WHERE List_name = '$list_name' AND LIst_member_id = '$user_name';";
            $store_result = $conn->query($store_sql);

            if ($store_result->num_rows > 0) {
                while ($store_row = $store_result->fetch_assoc()) {
                    $store_number = $store_row['LIst_store_number'];

                    // List_store_number를 사용하여 Store 테이블에서 상점 정보를 가져오는 쿼리
                    $store_info_sql = "SELECT Store_image, Store_name, Store_address, Store_number, Store_category 
                                       FROM Store 
                                       WHERE Store_number = '$store_number'";
                    $store_info_result = $conn->query($store_info_sql);

                    if ($store_info_result->num_rows > 0) {
                        while ($store_info_row = $store_info_result->fetch_assoc()) {
                            // 상점 정보를 배열에 저장
                            $store_info[] = $store_info_row;
                        }
                    }
                }
            }

            // List_name과 해당하는 상점 정보 배열을 연관 배열에 추가
            $list_names[$list_name] = $store_info;
        }
    }

        // 가게 평점 긁어오기
        $sql = "SELECT COUNT(*) AS count FROM Review";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $scores_length = $row['count'];

        // 가게 평점 데이터를 가져오는 SQL 쿼리
        $sql_scores = "SELECT Review_store_number, Review_score FROM Review LIMIT $scores_length";
        $result_scores = $conn->query($sql_scores);

        if ($result_scores) {
            // 데이터를 모두 가져와서 배열에 저장
            while ($row_score = $result_scores->fetch_assoc()) {
                $arr_store_number2[] = $row_score['Review_store_number'];
                $arr_score[] = $row_score['Review_score'];
            }
        } else {
             echo "Error in SQL for scores: " . $conn->error;
        }

        // 가게 평점 데이터를 가져오는 SQL 쿼리
        $sql_scores = "SELECT Review_store_number, Review_score FROM Review";
        $result_scores = $conn->query($sql_scores);

        // 각 가게의 누적 점수와 개수를 추적할 객체
        $storeStats = [];

        if ($result_scores->num_rows > 0) {
            while ($row_score = $result_scores->fetch_assoc()) {
                $store_number = $row_score['Review_store_number'];
                $score = $row_score['Review_score'];

                // 각 가게의 누적 점수와 개수를 저장
                if (!isset($storeStats[$store_number])) {
                    $storeStats[$store_number] = ['totalScore' => 0, 'count' => 0];
                }
                $storeStats[$store_number]['totalScore'] += $score;
                $storeStats[$store_number]['count']++;
            }
        }

        // 각 가게의 평균 평점을 계산하여 저장
        $storeAverages = [];
        foreach ($storeStats as $store_number => $stats) {
            $totalScore = $stats['totalScore'];
            $count = $stats['count'];
            $averageScore = round($totalScore / $count, 1); // 평균 평점을 소수점 한 자리까지 반올림
            $storeAverages[$store_number] = $averageScore;
        }
        

	foreach ($list_names as $list_name => $store_info) { 


	    echo "<li class='likeList_li'>";
	    echo "<h3 class='jjim_btn' onclick='toggleList(this)'>찜 목록 : $list_name</h3>";

	    // 삭제 폼 추가
	    echo "<form name='deleteForm_$list_name' method='post' action='delete_list.php' class='delete-form'>";
	    echo "<input type='hidden' name='member_id' value='$member_id'>";
	    echo "<input type='hidden' name='list_name' value='$list_name'>";
	    echo "<button type='button' class='delete-list-btn' onclick='confirmDelete(\"$list_name\");'>삭제</button>";
	    echo "</form>";

	    echo "<ul class='storelike_ul hidden'>";
	    foreach ($store_info as $store) {

	        $store_image = $store['Store_image'];
	        $store_name = $store['Store_name'];
	        $store_address = $store['Store_address'];
	        $store_number = $store['Store_number'];
	        $store_category = $store['Store_category'];

	        echo "<li class='storelike_li'>";
			echo "<div class='post-container' data-storeid='$store_number'>";
				echo "<img class='post-image' src='$store_image'></img>";
				echo "<div>";
					echo "<p class='post-name'>$store_name</p> <br> ";
					echo "<p class='post-address'>$store_address <br> #$store_category</p>";
				echo "</div>";
				echo "<div class='post-infoWrapper2'>";
					echo "<p class='post-score'>{$storeAverages[$store_number]}</p>";
					echo "<img class='post-goodimage' src='https://mblogthumb-phinf.pstatic.net/MjAyNDA1MTVfMTI2/MDAxNzE1NzgyNzE1ODEw.JHVI4aENjrfas4gtRIXCbs0n_m0AeQDaVZAvKFzkvM0g.VsALaHo8SkBAxEEo8MC9kcOE1aniJbqamg-R9LyE7wIg.PNG/rate_icon.png?type=w800'>";
				echo "</div>";
					echo "<form name='deleteStoreForm_$store_number' method='post' action='delete_store.php' class='delete-store-form'>";
					echo "<input type='hidden' name='member_id' value='$member_id'>";
					echo "<input type='hidden' name='list_name' value='$list_name'>";
					echo "<input type='hidden' name='store_number' value='$store_number'>";
					echo "<button type='button' class='delete-store-btn' onclick='confirmStoreDelete(\"$store_name\", \"$store_number\");'>X</button>";
					echo "</form>";
			echo "</div>";
		echo "</li>";
	    }
	    echo "</ul>";
	    echo "</li>";
	    echo "<hr>";
	    echo "<br>";
	}   
?>

<script>
	function confirmStoreDelete(storeName, storeNumber) {
	    var result = confirm("정말로 현재 찜목록에서 '" + storeName + "' 가게를 삭제하시겠습니까?");
	    if (result) {
	        // 삭제 폼 제출
	        document.forms["deleteStoreForm_" + storeNumber].submit();

	        // 삭제된 가게를 포함하는 요소를 삭제합니다.
	        var storeContainer = document.querySelector('.post-container[data-storeid="' + storeNumber + '"]');
	        storeContainer.parentNode.removeChild(storeContainer);
	    }
	}

	function toggleList(btn) {
	    // 클릭한 버튼의 부모 요소를 찾아서 다음 형제 요소를 토글
	    var list = btn.parentNode.querySelector('.storelike_ul');
	    list.classList.toggle('hidden'); // hidden 클래스를 토글
	}

	// post-container 클릭 이벤트 리스너를 추가하는 함수
	function updatePostContainerClickListeners() {
	    const postContainers = document.querySelectorAll('.post-container');

	    postContainers.forEach(function(container) {
	        container.addEventListener('click', function(event) {
	            // 삭제 버튼을 클릭한 경우에만 이동하지 않음
	            if (event.target.classList.contains('delete-store-btn')) {
	                return;
	            }

	            // 해당 postContainer의 가게 ID 가져오기
	            const storeID = container.getAttribute('data-storeid');

	            // 폼 생성
	            const form = document.createElement('form');
	            form.setAttribute('action', 'store_info.php');
	            form.setAttribute('method', 'post');

	            // 가게 번호를 전송하는 hidden input 추가
	            const inputStoreNumber = document.createElement('input');
	            inputStoreNumber.setAttribute('type', 'hidden');
	            inputStoreNumber.setAttribute('name', 'store_number');
	            inputStoreNumber.setAttribute('value', storeID);

	            // 폼에 hidden input 추가
	            form.appendChild(inputStoreNumber);

	            // 폼을 문서에 추가하고 자동으로 서브밋
	            document.body.appendChild(form);
	            form.submit();
	        });
	    });
	}

	// 초기 클릭 이벤트 리스너 설정
	updatePostContainerClickListeners();
</script>



<?php
    // 데이터베이스 연결 해제
    $conn->close();
?>