<?php
    // delete_list.php 파일

    // SQL 연결 설정 파일을 포함합니다.
    include("./SQLconstants.php");

    $conn = mysqli_connect($mySQL_host, $mySQL_id, $mySQL_password, $mySQL_database) or die("Can't access DB");

    // POST 요청으로부터 member_id와 list_name을 받아옵니다.
    $member_id = $_POST['member_id'];
    $list_name = $_POST['list_name'];

    // SQL 쿼리를 작성합니다. 해당 회원의 찜 목록을 삭제하는 쿼리입니다.
    $sql = "DELETE FROM Favorite_list WHERE List_member_id = '$member_id' AND List_name = '$list_name'";

    // SQL 쿼리를 실행합니다.
    if ($conn->query($sql) === TRUE) {
        // 성공적으로 삭제되면 성공 메시지를 반환합니다.
        echo "<script>console.log('찜 목록이 성공적으로 삭제되었습니다.');</script>";
    } else {
        // 삭제 중 오류가 발생하면 오류 메시지와 함께 오류 상세 정보를 반환합니다.
        echo "<script>console.log('오류: " . $sql . "<br>" . mysqli_error($conn) . "');</script>";
    }

    // 데이터베이스 연결을 닫습니다.
    $conn->close();

    // 페이지 리디렉션
    echo "<script>window.location.href = 'mypage.php';</script>";
?>