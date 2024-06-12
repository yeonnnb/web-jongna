<?php
	include("./SQLconstants.php");
	$conn = mysqli_connect($mySQL_host,$mySQL_id,$mySQL_password,$mySQL_database);

	if ($conn->connect_error) {
        	die("연결 실패: " . $conn->connect_error);
	}

	$sql = "
        WITH AvgReviewScores AS (
            SELECT
                Review_store_number,
                AVG(Review_score) AS avg_review_score
            FROM
                Review
            GROUP BY
                Review_store_number
        )
        UPDATE
            Store s
        SET
            Store_review_point = (
                SELECT
                    avg_review_score
                FROM
                    AvgReviewScores ars
                WHERE
                    s.Store_number = ars.Review_store_number
            );
	";

	if ($conn->multi_query($sql) === FALSE) {
        	echo "오류: 리뷰 업데이트 실패";
	}
	$conn->close();
?>
