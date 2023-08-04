<?php
session_start(); // Start the session

include('db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $message = $_POST["message"];
    $is_edited_by_admin = 0;

    // Check if name and email are empty
    if (empty($name) || empty($email)) {
        echo "Name and email are mandatory!";
        exit();
    }

    // Check if an image file is selected
    if (!empty($_FILES["image"]["name"])) {
        // Get file info
        $fileName = basename($_FILES["image"]["name"]);
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION);

        // Allow certain file formats
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
        if (!in_array($fileType, $allowTypes)) {
            echo "Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.";
            exit();
        }

        if ($_FILES["image"]["size"] > 1000000) {
            echo "File is too large. Max size allowed is 1MB.";
            exit();
        }

        // Move the temp image file to the images/ directory
        $targetPath = "images/" . $fileName;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            // Insert the image path into the database along with other data
            $sql = "INSERT INTO review (name, email, message, image, is_edited_by_admin) 
                    VALUES ('$name', '$email', '$message', '$targetPath', $is_edited_by_admin)";
        } else {
            echo "Error moving the uploaded file.";
            exit();
        }
    } else {
        // If no image is selected, insert the data without the image
        $sql = "INSERT INTO review (name, email, message, is_edited_by_admin) 
                VALUES ('$name', '$email', '$message', $is_edited_by_admin)";
    }

    // Execute the SQL query
    mysqli_query($conn, $sql);
    header("Location: index.php");
    exit();
}
?>
