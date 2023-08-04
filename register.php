<?php
include('db_connect.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $is_admin = isset($_POST["is_admin"]) ? 1 : 0;
    $hashedPassword = md5($password);

    $pre_sql = "SELECT * FROM users WHERE username='$username'";
    $pre_result = mysqli_query($conn, $pre_sql);
    if (mysqli_num_rows($pre_result) == 1) {
        $message = "Username is already taken";
    }
    else{
        $sql = "INSERT INTO users (username, password, is_admin) VALUES ('$username', '$hashedPassword', $is_admin)";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $message = "You have been successfully registered";
        } else {
            $message = "Registration failed";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Page</title>
</head>
<body>
    <h2>Registration</h2>
    <?php if (isset($message)) : ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
    <form action="register.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>

        <label for="is_admin">Admin:</label>
        <input type="checkbox" id="is_admin" name="is_admin" value="1">

        <input type="submit" value="Register">
    </form>
    <span>Already has an account?</span>
    <a href="login.php">Login</a>
</body>
</html>
