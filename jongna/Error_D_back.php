<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

$postData = null;
$id = null;
$storeNumber = null;
$category = null;
$message = null;
$cate = null;

// Check if POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Directly use POST data
    $postData = $_POST;
    $id = $postData['id'];
    $storeNumber = isset($_POST['store_number']) ? $_POST['store_number'] : null;
    $cate = isset($_GET['category']) ? $_GET['category'] : null;
    if($cate=="검색"){
	    $message = isset($_POST['message']) ? $_POST['message'] : null;
    }

    // Save POST data to session
    $_SESSION['post_data'] = $postData;

    // Process the POST data
    // Example: $data = processPostData($postData);
    header("Location: " . $_SERVER['REQUEST_URI'] . "?id=" . urlencode($id) . "&store_number=" . urlencode($storeNumber));
    exit();

} elseif (isset($_GET['id']) || isset($_SESSION['post_data'])) {
    // Fallback to GET or session data if available
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
	$storeNumber = $_GET['store_number'];
	$category = $_GET['categoty'];
	$message = $_GET['message'];
    } elseif (isset($_SESSION['post_data'])) {
        $postData = $_SESSION['post_data'];
        $id = $postData['id'];
	$storeNumber = isset($postData['store_number']) ? $postData['store_number'] : null;
	$category = isset($postData['category']) ? $postData['category'] : null;
	$message = isset($postData['message']) ? $postData['message'] : null;
    }

    // Fetch data based on $id or $storeNumber
    // Example: $data = fetchDataById($id);
} else {
    // Handle the case where neither POST nor GET data is available
    // Set a default action or value for initial load
    $id = null; // or any default value
    $storeNumber = null;
    $category = null;
    $message = null;
    // Example: $data = loadDefaultData();
}
?>
