<?php
session_start(); // 세션 시작

// 세션에서 사용자 ID 가져오기
$member_id = $_SESSION['username'];

// 데이터베이스 연결 설정
include('./SQLconstants.php');

// 데이터베이스 연결 생성
$conn = new mysqli($mySQL_host,$mySQL_id,$mySQL_password,$mySQL_database);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 이전 Review_number 가져오기
$sql = "SELECT Review_number FROM Review ORDER BY Review_number DESC LIMIT 1";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $last_review_number = $row["Review_number"];
    // 마지막 Review_number에서 숫자 부분 추출
    $last_number = intval(substr($last_review_number, 2)); // R_000002 -> 000002
    // 새로운 Review_number 생성
    $new_number = str_pad($last_number + 1, 6, "0", STR_PAD_LEFT); // 다음 숫자를 6자리로 맞추어 채우기
    $new_review_number = "R_" . $new_number;
} else {
    // 만약 이전 레코드가 없으면 초기값으로 설정
    $new_review_number = "R_000001";
}

// POST 값을 가져오기
$rateNum = $_POST['rateNum'];
$reviewContent = $_POST['reviewContent'];
$storeNumber = $_POST['storeNumber'];

//유효성 검사
$sql_check = "SELECT Review_number FROM Review WHERE Review_number = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $new_review_number);
$stmt_check->execute();
$stmt_check->store_result();
if ($stmt_check->num_rows > 0) {
    // 이미 사용 중인 리뷰 번호가 있다면 다른 번호를 생성
    $new_number = str_pad($last_number + 2, 6, "0", STR_PAD_LEFT); // 다음 숫자를 6자리로 맞추어 채우기
    $new_review_number = "R_" . $new_number;
}
$stmt_check->close();

// 사용자 입력 값의 유효성 검사
if (!isset($rateNum) || !isset($reviewContent) || !isset($storeNumber)) {
    // 필수 값이 누락되었을 경우 에러 메시지 출력
    echo "필수 값이 누락되었습니다.";
    exit;
}

// SQL 쿼리 작성하여 데이터베이스에 값 삽입
$sql = "INSERT INTO Review (Review_number, Review_member_id, Review_store_number, Review_text, Review_score, Review_day) VALUES (?, ?, ?, ?, ?, CURDATE())";

// SQL 쿼리를 실행하기 위해 준비
$stmt = $conn->prepare($sql);

// 바인딩 매개변수 설정
$stmt->bind_param("sssss", $new_review_number, $member_id, $storeNumber, $reviewContent, $rateNum);

// 쿼리 실행
//

if ($stmt->execute()) {
	echo "데이터베이스에 레코드가 성공적으로 추가되었습니다.";
	include ('./updata_review.php');
} else {
    echo "에러: " . $sql . "<br>" . $conn->error;
}
$stmt->close();
// 연결 종료
$conn->close();
?>
