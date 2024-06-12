<?php
  include("./SQLconstants.php");
  $conn = mysqli_connect($mySQL_host,$mySQL_id,$mySQL_password,$mySQL_database) or die ("Can't access DB");

  if($conn->connect_error){
    die("Connection failed: ". $conn->connect_error);
  }
?>

<?php

  // 데이터 가져오기
  session_start();
  $user_name = $_SESSION['username'];

  $sql = "SELECT Member_id, Member_name, Member_password FROM Member WHERE Member_id = '$user_name'"; 
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