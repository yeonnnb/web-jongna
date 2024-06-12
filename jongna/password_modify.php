<?php
    // delete_list.php 파일

    // SQL 연결 설정 파일을 포함합니다.
    include("./SQLconstants.php");

    $conn = mysqli_connect($mySQL_host, $mySQL_id, $mySQL_password, $mySQL_database) or die("Can't access DB");

    session_start();
    $user_name = $_SESSION['username'];

    // 변경 후 닉네임을 사용자가 입력한 값으로 가져옴
    $new_password = trim($_POST['new_password']); // 이 부분은 사용자가 변경 후 닉네임을 입력한 input의 name에 따라서 변경해야 함

    $sql = "UPDATE Member SET Member_password = '$new_password' WHERE Member_id = '$user_name'";

    if ($conn->query($sql) === TRUE) {
        // 비밀번호가 변경되었으므로 세션을 업데이트합니다.
        $_SESSION['password'] = $new_password;
        echo "<script>console.log('비밀번호가 성공적으로 변경되었습니다.');</script>";
    } else {
        echo "<script>console.log('Error: " . $sql . "<br>" . $conn->error . "');</script>";
    }

    // 데이터베이스 연결을 닫습니다.
    $conn->close();

    // 페이지 리디렉션
    echo "<script>window.location.href = 'mypage.php';</script>";
?>