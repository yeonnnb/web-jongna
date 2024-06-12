<?php
    // delete_list.php 파일

    // SQL 연결 설정 파일을 포함합니다.
    include("./SQLconstants.php");

    $conn = mysqli_connect($mySQL_host, $mySQL_id, $mySQL_password, $mySQL_database) or die("Can't access DB");

    
    // POST로 전달된 변경 후 아이디 값 가져오기
    $new_member_id = trim($_POST['new_member_id']);

    // Member 테이블에서 중복 확인
    $sql_check_duplicate = "SELECT * FROM Member WHERE Member_id='$new_member_id'";
    $result_check_duplicate = $conn->query($sql_check_duplicate);

    if ($result_check_duplicate->num_rows > 0) {
        // 중복되는 아이디가 존재할 경우
         echo "<script>alert('이미 사용 중인 아이디입니다. 다른 아이디를 사용해주세요.');</script>";
    } else {
        // 중복되는 아이디가 없는 경우
        echo "<script>
        if (confirm('사용 가능한 아이디입니다. 변경하시겠습니까?')) {
            window.location.href = 'id_update.php?new_member_id=$new_member_id';
        } else {
            window.location.href = 'mypageModify_id.php';
        }
    </script>";    }

    // 데이터베이스 연결을 닫습니다.
    $conn->close();

    // 페이지 리디렉션
    echo "<script>window.location.href = 'mypageModify_id.php';</script>";
?>