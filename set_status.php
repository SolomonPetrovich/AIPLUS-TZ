<?php
include('db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"]) && isset($_GET["is_published"])) {
    $reviewId = $_GET["id"];
    $isPublished = $_GET["is_published"];

    if (!is_numeric($reviewId) || !in_array($isPublished, array(0, 1))) {
        echo "Invalid parameters.";
        exit();
    }

    $sql = "UPDATE review SET is_published=$isPublished WHERE id=$reviewId";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Failed to update review status.";
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}
?>
