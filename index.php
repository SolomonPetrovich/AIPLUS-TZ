<?php
session_start();

include('db_connect.php');

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'date';

$allowedSortColumns = array('name', 'email', 'date');
if (!in_array($sort, $allowedSortColumns)) {
    $sort = 'date';
}

$order = isset($_GET['order']) ? $_GET['order'] : 'desc';

$allowedSortOrders = array('asc', 'desc');
if (!in_array($order, $allowedSortOrders)) {
    $order = 'desc';
}

//make query select only is_published=true
$sql = "SELECT * FROM review WHERE is_published=1 ORDER BY $sort $order";
$result = mysqli_query($conn, $sql);

$reviews = array();

while ($row = mysqli_fetch_assoc($result)) {
    $reviews[] = $row;
}

$loggedInUser = isset($_SESSION['username']) ? $_SESSION['username'] : null;

$is_admin = isset($_SESSION['is_admin']) ? $_SESSION['is_admin'] : null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Feedback Form</title>
</head>
<body>
    <?php if ($loggedInUser) : ?>
        <h1>Welcome, <?php echo $loggedInUser; ?>!</h1>
        <?php if ($is_admin) : ?>
            <a href="admin_dashboard.php">Admin Dashboard</a>
        <?php endif; ?>
        <br>
        <a href="logout.php">Logout</a>
    <?php else : ?>
        <a href="login.php">Login</a> | <a href="register.php">Register</a>
    <?php endif; ?>

    <h3>Existing Reviews:</h3>
    <p>Sort by:
        <a href="?sort=name&order=<?php echo ($sort == 'name' && $order == 'asc') ? 'desc' : 'asc'; ?>">Name</a> |
        <a href="?sort=email&order=<?php echo ($sort == 'email' && $order == 'asc') ? 'desc' : 'asc'; ?>">Email</a> |
        <a href="?sort=date&order=<?php echo ($sort == 'date' && $order == 'asc') ? 'desc' : 'asc'; ?>">Date</a>
    </p>

    <?php if (count($reviews) > 0) : ?>
        <ul>
            <?php foreach ($reviews as $review) : ?>
                <li>
                    <strong>Name:</strong> <?php echo $review['name']; ?><br>
                    <strong>Email:</strong> <?php echo $review['email']; ?><br>
                    <strong>Message:</strong> <?php echo $review['message']; ?><br>
                    <?php if ($review['is_edited_by_admin']) {
                        echo '<i>Edited by Admin</i> <br>';
                    }
                    ?>
                    <strong>Date and Time:</strong> <?php echo $review['date']; ?><br>
                    <?php if ($review['image']) : ?>
                        <img src="<?php echo $review['image']; ?>" width="200">
                    <?php endif; ?>
                </li>
                <br>
            <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <p>No reviews yet.</p>
    <?php endif; ?>
    <h3>Leave a Review:</h3>
    <form action="submit_feedback.php" method="post" enctype="multipart/form-data">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>

        <label for="message">Message:</label>
        <textarea id="message" name="message" rows="4" required></textarea>
        <br>

        <label for="image">Attach Image (JPG, GIF, PNG, max 1MB):</label>
        <input type="file" accept="image/png, image/gif, image/jpeg" id="image" name="image">
        <br>

        <input type="submit" value="Submit">
    </form>

</body>
</html>
