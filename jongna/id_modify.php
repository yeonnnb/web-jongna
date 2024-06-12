<?php
    // SQL 연결 설정 파일을 포함합니다.
    include("./SQLconstants.php");

    // 데이터베이스 연결을 엽니다.
    $conn = mysqli_connect($mySQL_host, $mySQL_id, $mySQL_password, $mySQL_database);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    session_start();
    $user_name = $_SESSION['username'];

    // 변경 후 닉네임을 사용자가 입력한 값으로 가져옴
    $new_member_id = trim($_POST['new_member_id']); // 사용자가 변경 후 닉네임을 입력한 input의 name에 따라 변경 필요
    $new_member_id = mysqli_real_escape_string($conn, $new_member_id); // SQL 인젝션 방지

    // 트랜잭션 시작
    mysqli_begin_transaction($conn);

    try {
        // 외래 키 제약 조건을 일시적으로 해제
        mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=0");

        // SQL 쿼리를 실행하여 해당 Member_contact를 가진 회원의 Member_id를 업데이트
        $sql = "UPDATE Member SET Member_id = '$new_member_id' WHERE Member_id = '$user_name'";
        if (!mysqli_query($conn, $sql)) {
            throw new Exception("Error updating Member: " . mysqli_error($conn));
        }

        // 참조된 테이블 (Favorite_list)의 외래 키 필드를 업데이트
        $sql = "UPDATE Favorite_list SET LIst_member_id = '$new_member_id' WHERE LIst_member_id = (SELECT Member_id FROM Member WHERE Member_id = '$user_name')";
        if (!mysqli_query($conn, $sql)) {
            throw new Exception("Error updating Favorite_list: " . mysqli_error($conn));
        }

        // 외래 키 제약 조건을 다시 설정
        mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=1");

        // 세션 업데이트
        $_SESSION['username'] = $new_member_id;

        // 트랜잭션 커밋
        mysqli_commit($conn);

        echo "<script>console.log('아이디가 성공적으로 변경되었습니다.');</script>";
    } catch (Exception $e) {
        // 트랜잭션 롤백
        mysqli_rollback($conn);

        echo "<script>console.log('Error: " . $e->getMessage() . "');</script>";
    }

    // 데이터베이스 연결을 닫습니다.
    mysqli_close($conn);

    // 페이지 리디렉션 (필요에 따라 주석 해제)
    echo "<script>window.location.href = 'mypage.php';</script>";

?>