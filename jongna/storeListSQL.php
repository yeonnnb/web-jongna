<?php
  include("./SQLconstants.php");
  $conn = mysqli_connect($mySQL_host,$mySQL_id,$mySQL_password,$mySQL_database) or die ("Can't access DB");

  if($conn->connect_error){
    die("Connection failed: ". $conn->connect_error);
  }

  // URL 매개변수에서 category 값을 가져옴
	$category = isset($_GET['category']) ? $_GET['category'] : '';
?>

<!-- 배열 길이 구하기 -->
<?php

  	
	$message = $_POST['message'];
        $message = ((($message == null) || ($message == "") || (strncmp($message, " * ", 3) == 0)) ? "_%" : $message);

  $jg = 0;

  if($category=="한식"){
    $sql = "SELECT COUNT(*) AS count FROM Store WHERE Store_category = '한식' " ;
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $data_length = $row['count'];
  }
  elseif($category=="양식"){ 
    $sql = "SELECT COUNT(*) AS count FROM Store WHERE Store_category = '양식' " ;
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $data_length = $row['count'];
  }
  elseif($category=="중식"){ 
    $sql = "SELECT COUNT(*) AS count FROM Store WHERE Store_category = '중식' " ;
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $data_length = $row['count'];
  }
  elseif($category=="일식"){ 
    $sql = "SELECT COUNT(*) AS count FROM Store WHERE Store_category = '일식' " ;
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $data_length = $row['count'];
  }
  elseif($category=="카페"){ 
    $sql = "SELECT COUNT(*) AS count FROM Store WHERE Store_category = '카페' " ;
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $data_length = $row['count'];
  }
  elseif($category=="주점"){ 
    $sql = "SELECT COUNT(*) AS count FROM Store WHERE Store_category = '주점' " ;
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $data_length = $row['count'];
  }
  elseif($category=="검색"){ 
    // Store 테이블에서 검색어와 일치하는 가게 가져오기
    $store_ids_from_store = [];
    $store_sql = "SELECT Store_number FROM Store WHERE 
                    store_name LIKE '%$message%' OR 
                    store_category LIKE '%$message%' OR 
                    store_contact LIKE '%$message%' OR 
                    store_time LIKE '%$message%' OR 
                    store_address LIKE '%$message%'";
    $store_result = $conn->query($store_sql);

    if ($store_result) {
        while ($row = $store_result->fetch_assoc()) {
            $store_ids_from_store[] = $row['Store_number'];
        }
    }

    // Review 테이블에서 검색어와 일치하는 store_id 가져오기
    $store_ids_from_review = [];
    $review_sql = "SELECT DISTINCT Review_store_number FROM Review WHERE Review_recommend LIKE '%$message%' OR Review_text LIKE '%$message%'";
    $review_result = $conn->query($review_sql);

    if ($review_result) {
        while ($row = $review_result->fetch_assoc()) {
            $store_ids_from_review[] = $row['Review_store_number'];
        }
    }

    // 두 배열을 병합하고 중복 제거
    $store_ids = array_unique(array_merge($store_ids_from_store, $store_ids_from_review));

    $jg = 1;
    $store_ids_length = count($store_ids);
  }

  // 가게 평점 긁어오기
  $sql = "SELECT COUNT(*) AS count FROM Review";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  $scores_length = $row['count'];
?>

<!-- 배열 길이 구하기 -->
<script>
    // php에서 js로 변수값 전달
    var Category = "<?php echo isset($_GET['category']) ? $_GET['category'] : ''; ?>";
    let dataLength = 0;
    if(Category=="검색"){
        <?php echo "dataLength = '$store_ids_length';"; ?>
    }
    else{
        <?php echo "dataLength = '$data_length';"; ?>
    }
    <?php echo "let scoresLength = '$scores_length';"; ?>
</script>

<?php 

  $arr_image = [];
  $arr_name = [];
  $arr_address = [];
  $arr_store_number1 = [];
  $arr_store_number2 = [];
  $arr_score = [];

  if($jg==1){
    if (!empty($store_ids)) {
        // $store_ids를 문자열로 변환
        $store_ids_string = "'" . implode("','", $store_ids) . "'";

        // SQL 쿼리 생성
        $final_store_sql = "SELECT Store_image, Store_name, Store_address, Store_number, Store_category FROM Store WHERE Store_number IN ($store_ids_string)";

        // 쿼리 실행
        $final_store_result = $conn->query($final_store_sql);

        if ($final_store_result) {
            // 결과 처리
            while ($row = $final_store_result->fetch_assoc()) {
                $arr_image[] = $row['Store_image'];
                $arr_name[] = $row['Store_name'];
                $arr_address[] = $row['Store_address'];
                $arr_store_number1[] = $row['Store_number'];
                $arr_category[] = $row['Store_category'];
            }
        } else {
            // 오류 처리
            echo "Error in final store SQL: " . $conn->error;
        }
    }
  }
  else{
    // SQL 쿼리를 조건문을 사용하여 한 번에 작성
      $sql = "SELECT Store_image, Store_name, Store_address, Store_number, Store_category FROM Store WHERE Store_category = '$category' LIMIT $data_length";
      $result = $conn->query($sql);

      if ($result) {
          // 데이터를 모두 가져와서 배열에 저장
          while ($row = $result->fetch_assoc()) {
              $arr_image[] = $row['Store_image'];
              $arr_name[] = $row['Store_name'];
              $arr_address[] = $row['Store_address'];
              $arr_store_number1[] = $row['Store_number'];
              $arr_category[] = $row['Store_category'];
          }
      } else {
          echo "Error in SQL for category '$category': " . $conn->error;
      } 
  }
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

  $conn->close()
?>

<script>
    // php에서 js로 배열 전달
    let arrImage = <?php echo json_encode($arr_image) ?>; 
    let arrName = <?php echo json_encode($arr_name) ?>;
    let arrAddress = <?php echo json_encode($arr_address) ?>;
    let arrStoreID1 = <?php echo json_encode($arr_store_number1) ?>;
    let arrCategory = <?php echo json_encode($arr_category) ?>;

    //data 배열 생성
    const data = [];

    for(let i=0; i < dataLength; i++){
      data.push({
        StoreID : arrStoreID1[i],
        StoreImage: arrImage[i],
        StoreName: arrName[i],
        StoreAddress: arrAddress[i],
        StoreCategory: arrCategory[i]
      });
    }

    let arrStoreID2 = <?php echo json_encode($arr_store_number2) ?>;
    let arrScore = <?php echo json_encode($arr_score) ?>;

    const scores = [];
    for(let i=0; i < scoresLength; i++){
      scores.push({
        StoreID : arrStoreID2[i],
        StoreScore : arrScore[i]
      });
    }
        
    // 각 가게의 누적 점수와 개수를 추적할 객체
    const storeStats = {};

    // 배열을 순회하면서 누적 점수와 개수를 추적
    scores.forEach(score => {
        const { StoreID, StoreScore } = score;
        // 문자열을 숫자로 변환하여 StoreScore에 저장
        const scoreNum = parseInt(StoreScore);
        if (!storeStats[StoreID]) {
            storeStats[StoreID] = { totalScore: 0, count: 0 };
        }
        storeStats[StoreID].totalScore += scoreNum; // 숫자로 변환한 값 사용
        storeStats[StoreID].count++;
    });

    // 각 가게에 대한 평균 점수를 계산하고 소수점 한 자리까지 반올림
    const storeAverages = {};
    for (const storeID in storeStats) {
      const { totalScore, count } = storeStats[storeID];
      const averageScore = totalScore / count;
      storeAverages[storeID] = averageScore.toFixed(1); // 소수점 한 자리까지 반올림
    }

    // storeAverages 객체를 배열 형태로 변환하여 storeAveragesArray에 저장
    const storeAveragesArray = Object.entries(storeAverages).map(([StoreID, StoreScore]) => ({ StoreID, StoreScore }));

    // data 배열에 StoreScore 추가
    data.forEach(item => {
      const match = storeAveragesArray.find(avg => avg.StoreID === item.StoreID);
      if (match) {
        item.StoreScore = match.StoreScore;
      } else {
        item.StoreScore = null; // 평점이 없는 경우
      }
    });
    

    const COUNT_PER_PAGE = 5; // 페이지 당 보여줄 게시물 수
    const numberButtonWrapper = document.querySelector('.number-button-wrapper'); // 페이지네이션 버튼 wrapper
    const ul = document.querySelector('.storelist_ul'); // 게시물을 담을 unordered list
    const prevButton = document.querySelector('.prev-button'); // 이전 페이지 버튼
    const nextButton = document.querySelector('.next-button'); // 이후 페이지 버튼
    let pageNumberButtons; // 페이지 버튼들

    let currentPage = 1; // 초기 페이지 번호

    // 필요한 페이지 번호 수 구하기
    // Math.ceil는 올림함수
    const getTotalPageCount = function() {
      return Math.ceil(dataLength / COUNT_PER_PAGE);
    };

    // 페이지 번호 버튼 동적으로 생성하는 함수
    const setPageButtons = function() {
      numberButtonWrapper.innerHTML = ''; // 비우기(초기화)

      for (let i = 1; i <= getTotalPageCount(); i++) {
        numberButtonWrapper.innerHTML += `<span class="number-button"> ${i} </span>`;
      }

      numberButtonWrapper.firstChild.classList.add('selected');
      pageNumberButtons = document.querySelectorAll('.number-button');
    };

    // 페이지에 해당하는 게시물 ul에 넣어주기
    
    const setPageOf = (pageNumber) => {
      ul.innerHTML = '';

      // ex) pageNumber = 3 이면, 3번째 페이지 보여줘야 하기 때문에 11번째 부터 시작해야함. 5 * ( 3 - 1 ) + 1 = 11 
      for (let i = COUNT_PER_PAGE * (pageNumber - 1) + 1; i <= COUNT_PER_PAGE * (pageNumber - 1) + 5 && i <= dataLength; i++) {
        const li = document.createElement('li');

        // 컨테이너
        const postContainer = document.createElement('div');
        postContainer.className = 'post-container';
        postContainer.setAttribute('data-storeid', data[i - 1].StoreID);

        // 사진
        const postImage = document.createElement('img'); 
        postImage.className = 'post-image';
        
        // 가게 이름
        const postName = document.createElement('p');
        postName.className = 'post-name';

        // 가게 주소
        const postAddress = document.createElement('p');
        postAddress.className = 'post-address';

        // 따봉 사진
        const postGoodImage = document.createElement('img'); 
        postGoodImage.className = 'post-goodimage';

        // 가게 평점
        const postScore = document.createElement('p');
        postScore.className = 'post-score';

        postImage.src = data[i - 1].StoreImage; // 이미지 주소를 src 속성으로 설정
        postName.innerHTML = data[i - 1].StoreName + "<br>" + "<br>";
        postAddress.innerHTML = "주소 : " + data[i - 1].StoreAddress + "<br>" + "#" + data[i - 1].StoreCategory + "<br>";
        postGoodImage.src = "https://mblogthumb-phinf.pstatic.net/MjAyNDA1MTVfMTI2/MDAxNzE1NzgyNzE1ODEw.JHVI4aENjrfas4gtRIXCbs0n_m0AeQDaVZAvKFzkvM0g.VsALaHo8SkBAxEEo8MC9kcOE1aniJbqamg-R9LyE7wIg.PNG/rate_icon.png?type=w800"; // 이미지 주소를 src 속성으로 설정
        postScore.innerHTML = data[i - 1].StoreScore;

        // 가게 이름과 주소를 하나의 div 요소로 묶어서 postContainer에 추가
        const infoWrapper1 = document.createElement('div');
        infoWrapper1.appendChild(postName);
        infoWrapper1.appendChild(postAddress);

        // 따봉과 평점을 하나의 div 요소로 묶어서 postContainer에 추가
        const infoWrapper2 = document.createElement('div');
        infoWrapper2.className = 'post-infoWrapper2';

	infoWrapper2.appendChild(postScore);
	infoWrapper2.appendChild(postGoodImage);

        postContainer.append(postImage, infoWrapper1, infoWrapper2); // 이미지와 가게 정보를 추가
        li.append(postContainer);
        ul.append(li);
      }
      // 새로 생성된 post-container 요소에 클릭 이벤트 리스너 추가
      updatePostContainerClickListeners();
    };

     // 페이지 이동에 따른 css 
    const moveSelectedPageHighlight = function() {
      const pageNumberButtons = document.querySelectorAll('.number-button'); // 페이지 버튼들

      pageNumberButtons.forEach(function(numberButton) {
        if (numberButton.classList.contains('selected')) {
          numberButton.classList.remove('selected');
        }
      });

      pageNumberButtons[currentPage - 1].classList.add('selected');
    };

    setPageButtons();
    setPageOf(currentPage);

    // 페이지번호 버튼 클릭 리스너
    pageNumberButtons.forEach(function(numberButton)  {
      numberButton.addEventListener('click', function(e) {
        currentPage = +e.target.innerHTML;
        console.log(currentPage);
        setPageOf(currentPage);
        moveSelectedPageHighlight();
      });
    });

    // 이전 버튼 클릭 리스너
    prevButton.addEventListener('click', function() {
      if (currentPage > 1) {
        currentPage -= 1;
        setPageOf(currentPage);
        moveSelectedPageHighlight();
      }
    });

    // 이후 버튼 클릭 리스너
    nextButton.addEventListener('click', function() {
      if (currentPage < getTotalPageCount()) {
        currentPage += 1;
        setPageOf(currentPage);
        moveSelectedPageHighlight();
      }
    });

    let isDescending = true; // 정렬 방식 toggle 변수

   // '평점 순' 버튼 클릭 리스너
document.querySelector('.sort_container').addEventListener('click', function() {
  // 정렬 방식 toggle
  isDescending = !isDescending;

  // 평점 순으로 data 배열 정렬
  data.sort((a, b) => {
    // 기본값 설정: 평점이 없는 경우 0으로 간주
    const scoreA = a.StoreScore || 0;
    const scoreB = b.StoreScore || 0;
    if (isDescending) {
      // 오름차순 정렬
      return scoreA - scoreB;
    } else {
      // 내림차순 정렬
      return scoreB - scoreA;
    }
  });

  // 현재 페이지를 첫 페이지로 설정
  currentPage = 1;

  // 페이지네이션 버튼과 게시물 업데이트
  setPageButtons();
  setPageOf(currentPage);
  moveSelectedPageHighlight();

  // 페이지번호 버튼 클릭 리스너 업데이트
  pageNumberButtons.forEach(function(numberButton) {
    numberButton.addEventListener('click', function(e) {
      currentPage = +e.target.innerHTML;
      setPageOf(currentPage);
      moveSelectedPageHighlight();
    });
  });

  // 정렬 후 post-container 클릭 이벤트 리스너 업데이트
  updatePostContainerClickListeners();
});

// post-container 클릭 이벤트 리스너를 추가하는 함수
function updatePostContainerClickListeners() {
  const postContainers = document.querySelectorAll('.post-container');

  postContainers.forEach(function(container) {
    container.addEventListener('click', function() {
      // 해당 postContainer의 가게 ID 가져오기
      const storeID = container.getAttribute('data-storeid');

      // 폼 생성
      const form = document.createElement('form');
      form.setAttribute('action', 'store_info.php');
      form.setAttribute('id', 'store_number_load');
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

