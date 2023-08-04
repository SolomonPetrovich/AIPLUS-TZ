<?php
session_start();

// Redirect to the login page if admin is not logged in
if (!isset($_SESSION['is_admin'])) {
    header('Location: login.php');
    exit;
}

include('db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reviewId = $_POST['id'];
    $editedMessage = $_POST['message'];

    // Update the review in the database
    $sql = "UPDATE review SET message='$editedMessage', is_edited_by_admin=1 WHERE id=$reviewId";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $successMessage = "Review edited successfully.";
    } else {
        $errorMessage = "Failed to edit review.";
    }
}

// Fetch the review data from the database if 'id' parameter is provided
if (isset($_GET['id'])) {
    $reviewId = $_GET['id'];
    $sql = "SELECT * FROM review WHERE id=$reviewId";
    $result = mysqli_query($conn, $sql);
    $review = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Review</title>
</head>
<body>
    <h2>Edit Review</h2>
    <?php if (isset($successMessage)) : ?>
        <p><?php echo $successMessage; ?></p>
    <?php endif; ?>
    <?php if (isset($errorMessage)) : ?>
        <p><?php echo $errorMessage; ?></p>
    <?php endif; ?>
    
    <?php if (isset($review)) : ?>
        <p><strong>Name:</strong> <?php echo $review['name']; ?></p>
        <p><strong>Email:</strong> <?php echo $review['email']; ?></p>
        <p><strong>Date and Time:</strong> <?php echo $review['date']; ?></p>
        
        <h3>Message:</h3>
        <form action="edit_review.php" method="post">
            <input type="hidden" name="id" value="<?php echo $review['id']; ?>">
            
            <label for="message">Original Message:</label>
            <textarea id="message" name="message" rows="4"><?php echo $review['message']; ?></textarea>
            <br>

            <input type="submit" value="Save">
        </form>
    <?php endif; ?>

    <a href="admin_dashboard.php">Back to Dashboard</a>
</body>
</html>
