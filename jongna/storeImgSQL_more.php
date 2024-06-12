<?php
include("./SQLconstants.php");
$conn = mysqli_connect($mySQL_host, $mySQL_id, $mySQL_password, $mySQL_database) or die ("Can't access DB");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 가게 번호 확인
if (isset($_GET['store_number'])) {
    $storeNumber = $_GET['store_number'];
} else {
    echo "Store_number 값이 전달되지 않았습니다.";
    exit;
}

// 페이지 네이션 변수 설정
$imagesPerPage = 16;
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
$offset = ($page - 1) * $imagesPerPage;

// 총 이미지 수 가져오기
$imageCountQuery = "SELECT COUNT(*) as total FROM StoreImage WHERE StoreImage_id = '$storeNumber'";
$imageCountResult = mysqli_query($conn, $imageCountQuery);
$imageCountRow = mysqli_fetch_assoc($imageCountResult);
$totalImages = $imageCountRow['total'];
$totalPages = ceil($totalImages / $imagesPerPage);

// 이미지 쿼리 4*4 배열 저장
$imageQuery = "SELECT * FROM StoreImage WHERE StoreImage_id = '$storeNumber' LIMIT $offset, $imagesPerPage";
$imageResult = mysqli_query($conn, $imageQuery);

echo "<div style='display: grid; grid-template-columns: repeat(4, 1fr); grid-gap: 10px;'>";

while ($row = mysqli_fetch_assoc($imageResult)) {
    echo "<div><img src='".$row['StoreImage_image_URL']."' width='200px' height='200px'></div>";
}

echo "</div>";

// 페이지 네이션 링크
echo "<div style='text-align: center; margin-top: 20px;'>";
if ($page > 1) {
    echo "<a href='storeImgSQL_more.php?store_number=$storeNumber&page=".($page-1)."' style='margin-right: 10px;'>이전 페이지</a>";
}
if ($page < $totalPages) {
    echo "<a href='storeImgSQL_more.php?store_number=$storeNumber&page=".($page+1)."'>다음 페이지</a>";
}
echo "</div>";

$conn->close();
?>
