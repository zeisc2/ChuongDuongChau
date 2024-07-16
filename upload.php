<?php
require 'config.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $target_dir = "uploads/";

    // Kiểm tra xem thư mục có tồn tại hay không, nếu không thì tạo thư mục
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_file = $target_dir . basename($_FILES["file"]["name"]);

    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO tracks (user_id, title, file_path) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $title, $target_file);
        $stmt->execute();
        $stmt->close();
        echo "The file " . htmlspecialchars(basename($_FILES["file"]["name"])) . " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Upload Music</title>
</head>

<body>
    <h1>Upload Music</h1>
    <form method="post" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Title" required>
        <input type="file" name="file" required>
        <button type="submit">Upload</button>
    </form>
    <a href="index.php">Quay về trang chủ</a>
</body>

</html>