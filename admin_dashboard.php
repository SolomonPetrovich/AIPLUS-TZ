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
    
    <!-- Display the list of reviews to edit -->
    <?php
    include('db_connect.php');
    
    $sql = "SELECT * FROM review ORDER BY date DESC";
    $result = mysqli_query($conn, $sql);
    
    $reviews = array();
    
    while ($row = mysqli_fetch_assoc($result)) {
        $reviews[] = $row;
    }
    
    if (count($reviews) > 0) {
        echo '<h3>Reviews to Edit:</h3>';
        echo '<ul>';
        foreach ($reviews as $review) {
            echo '<li>';
            echo '<strong>Name:</strong> ' . $review['name'] . '<br>';
            echo '<strong>Email:</strong> ' . $review['email'] . '<br>';
            echo '<strong>Message:</strong> ' . $review['message'] . '<br>';
            if ($review['is_edited_by_admin']) {
                echo '<i>Edited by Admin</i> <br>';
            }
            echo '<a href="edit_review.php?id=' . $review['id'] . '">Edit</a>';
            echo '</li><br>';
        }
        echo '</ul>';
    } else {
        echo '<p>No reviews to edit.</p>';
    }
    ?>
</body>
</html>
