<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>
    <h2>Admin Dashboard</h2>
    <a href="index.php">Main page</a><br>
    <a href="logout.php">Logout</a>
    <?php
    include('db_connect.php');
    
    // Function to toggle the status of a review (published/unpublished)
    function toggleStatus($conn, $reviewId, $isPublished) {
        $status = $isPublished ? 0 : 1;
        $sql = "UPDATE review SET is_published=$status WHERE id=$reviewId";
        mysqli_query($conn, $sql);
    }
    
    // Fetch published reviews
    $sql_published = "SELECT * FROM review WHERE is_published = 1 ORDER BY date DESC";
    $result_published = mysqli_query($conn, $sql_published);
    $published_reviews = mysqli_fetch_all($result_published, MYSQLI_ASSOC);
    
    // Fetch not published reviews
    $sql_not_published = "SELECT * FROM review WHERE is_published = 0 ORDER BY date DESC";
    $result_not_published = mysqli_query($conn, $sql_not_published);
    $not_published_reviews = mysqli_fetch_all($result_not_published, MYSQLI_ASSOC);
    
    echo '<h3>Published Reviews:</h3>';
    if (count($published_reviews) > 0) {
        echo '<ul>';
        foreach ($published_reviews as $review) {
            echo '<li>';
            echo '<strong>Name:</strong> ' . $review['name'] . '<br>';
            echo '<strong>Email:</strong> ' . $review['email'] . '<br>';
            echo '<strong>Message:</strong> ' . $review['message'] . '<br>';
            echo '<a href="edit_review.php?id=' . $review['id'] . '">Edit</a>';
            echo ' | ';
            echo '<button onclick="toggleStatus(' . $review['id'] . ', true)">Unpublish</button>';
            echo '</li><br>';
        }
        echo '</ul>';
    } else {
        echo '<p>No published reviews.</p>';
    }

    echo '<h3>Not Published Reviews:</h3>';
    if (count($not_published_reviews) > 0) {
        echo '<ul>';
        foreach ($not_published_reviews as $review) {
            echo '<li>';
            echo '<strong>Name:</strong> ' . $review['name'] . '<br>';
            echo '<strong>Email:</strong> ' . $review['email'] . '<br>';
            echo '<strong>Message:</strong> ' . $review['message'] . '<br>';
            echo '<a href="edit_review.php?id=' . $review['id'] . '">Edit</a>';
            echo ' | ';
            echo '<button onclick="toggleStatus(' . $review['id'] . ', false)">Publish</button>';
            echo '</li><br>';
        }
        echo '</ul>';
    } else {
        echo '<p>No not published reviews.</p>';
    }
    ?>

    <script>
        function toggleStatus(reviewId, isPublished) {
            if (confirm(isPublished ? "Are you sure you want to unpublish this review?" : "Are you sure you want to publish this review?")) {
                window.location.href = "set_status.php?id=" + reviewId + "&is_published=" + (isPublished ? 0 : 1);
            }
        }
    </script>
</body>
</html>
