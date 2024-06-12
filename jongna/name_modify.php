<?php
    // delete_list.php 파일

    // SQL 연결 설정 파일을 포함합니다.
    include("./SQLconstants.php");

    $conn = mysqli_connect($mySQL_host, $mySQL_id, $mySQL_password, $mySQL_database) or die("Can't access DB");

    session_start();
    $user_name = $_SESSION['username'];

    // $member_contact = '777-635-8902'; 

    // 변경 후 닉네임을 사용자가 입력한 값으로 가져옴
    $new_nickname = trim($_POST['new_nickname']); // 이 부분은 사용자가 변경 후 닉네임을 입력한 input의 name에 따라서 변경해야 함

    // SQL 쿼리를 실행하여 해당 Member_contact를 가진 회원의 Member_name을 업데이트
    $sql = "UPDATE Member SET Member_name = '$new_nickname' WHERE Member_id = '$user_name'";

    if ($conn->query($sql) === TRUE) {
        // 세션 업데이트
        $_SESSION['nickname'] = $new_nickname;
        echo "<script>console.log('닉네임이 성공적으로 변경되었습니다.');</script>";
    } else {
        echo "<script>console.log('Error: " . $sql . "<br>" . $conn->error . "');</script>";
    }

    // 데이터베이스 연결을 닫습니다.
    $conn->close();

    // 페이지 리디렉션
    echo "<script>window.location.href = 'mypage.php';</script>";
?>