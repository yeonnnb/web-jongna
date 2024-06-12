<?php
  include("./SQLconstants.php");
  $conn = mysqli_connect($mySQL_host,$mySQL_id,$mySQL_password,$mySQL_database) or die ("Can't access DB");

  if($conn->connect_error){
    die("Connection failed: ". $conn->connect_error);
  }
?>


<?php
    // 배열 길이 구하기
    $sql = "SELECT COUNT(DISTINCT List_name) AS count FROM Favorite_list WHERE LIst_member_id = 'BGRYX';";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $data_length = $row['count'];
    // echo "<script>console.log($data_length );</script>";

    // 가게 평점 긁어오기
    $sql = "SELECT COUNT(*) AS count FROM Review";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $scores_length = $row['count'];

    
// 찜목록 가게 리스트 이름 배열
$sql = "SELECT DISTINCT List_name FROM Favorite_list WHERE List_member_id = 'BGRYX';";
$result = $conn->query($sql);

$list_names = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $list_name = $row['List_name'];
        $store_info = [];

        // 각 List_name에 해당하는 List_store_number를 가져오는 쿼리
        $store_sql = "SELECT List_store_number FROM Favorite_list WHERE List_name = '$list_name' AND List_member_id = 'BGRYX';";
        $store_result = $conn->query($store_sql);

        if ($store_result->num_rows > 0) {
            while ($store_row = $store_result->fetch_assoc()) {
                $store_number = $store_row['List_store_number'];

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

// 배열 출력 (디버깅용)
echo "<script>console.log(" . json_encode($list_names) . ");</script>";

    
?>

<script>
    // php에서 js로 변수값 전달
    <?php echo "dataLength = '$store_ids_length';"; ?>
    <?php echo "let scoresLength = '$scores_length';"; ?>
</script>


<?php

  // 데이터 가져오기
  $sql = "SELECT Member_id, Member_name, Member_password FROM Member WHERE Member_id = 'BGRYX'"; // 고칠 부분 : 현재 로그인 한 사람이 누구냐에 따라 달라져야 함
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
      // 결과에서 데이터 가져오기
      $row = $result->fetch_assoc();
      $member_id = $row['Member_id'];
      $member_name = $row['Member_name'];
      $member_password = $row['Member_password'];
  } else {
      $member_id = "";
      $member_name = "";
      $member_password = "";
  }


    // 데이터베이스 연결 해제
    $conn->close();
?>
