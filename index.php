<?php
require 'config.php';
session_start();

// Lấy danh sách các bài hát từ cơ sở dữ liệu
$sql = "SELECT tracks.id, tracks.title, tracks.file_path, users.username 
        FROM tracks 
        INNER JOIN users ON tracks.user_id = users.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>3TL Music Hub</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <h1>Chào mừng bạn đến với 3TL Music Hub </h1>
    <?php if (isset($_SESSION['username'])) : ?>
        <p>Xin chào, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
        <a href="upload.php">Tải lên Nhạc</a>
        <a href="logout.php">Đăng xuất</a>
    <?php else : ?>
        <a href="login.php">Đăng nhập</a>
        <a href="register.php">Đăng ký</a>
    <?php endif; ?>

    <div class="tracks">
        <?php while ($row = $result->fetch_assoc()) : ?>
            <div class="track">
                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                <p>Được tải lên bởi: <?php echo htmlspecialchars($row['username']); ?></p>
                <audio controls>
                    <source src="<?php echo htmlspecialchars($row['file_path']); ?>" type="audio/mpeg">
                    Trình duyệt của bạn không hỗ trợ phát nhạc.
                </audio>
            </div>
        <?php endwhile; ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/howler/2.3.1/howler.min.js"></script>
    <script>
        const audioElements = document.querySelectorAll('audio');
        audioElements.forEach(audio => {
            const player = new Howl({
                src: [audio.src],
                html5: true
            });

            audio.addEventListener('play', () => {
                player.play();
            });

            audio.addEventListener('pause', () => {
                player.pause();
            });

            audio.addEventListener('seeked', () => {
                player.seek(audio.currentTime);
            });
        });
    </script>
</body>

</html>