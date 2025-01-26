<?php
date_default_timezone_set('Europe/Kiev');

$nonce = bin2hex(random_bytes(16));  

$file = 'VERSION';
$version = file_exists($file), file_get_contents($file) : 'Не определено.';

header("Content-Security-Policy: script-src 'self' 'nonce-$nonce'; style-src 'self' 'nonce-$nonce';");

include 'include/connect.php';

if (!isset($_COOKIE['user_id'])) {
    $userId = uniqid();
    setcookie('user_id', $userId, time() + 30 * 24 * 60 * 60, '/', '', true, true); // Добавлены флаги Secure и HttpOnly
} else {
    $userId = $_COOKIE['user_id'];
}

$query = $pdo->prepare("SELECT * FROM notes WHERE user_id = :user_id ORDER BY created_at DESC");
$query->bindParam(':user_id', $userId);
$query->execute();

$notes = [];

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $notes[] = $row;
}

$countQuery = $pdo->prepare("SELECT COUNT(*) AS note_count FROM notes WHERE user_id = :user_id");
$countQuery->bindParam(':user_id', $userId);
$countQuery->execute();
$countResult = $countQuery->fetch(PDO::FETCH_ASSOC);
$noteCount = $countResult['note_count'];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заметки</title>

    <!-- Применение nonce для стилистического файла -->
    <link rel="stylesheet" href="assets/css/index.css" type="text/css" nonce="<?= $nonce ?>">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
<?php include 'include/html/header.php' ?>
    <div class="pk-main">
        <div class="pk-info-block">
            <div class="pk-info-block__note-count">
                <p>Количество заметок: <?= htmlspecialchars($noteCount); ?>.</p>
            </div>
        </div>
    <div class="pk-notes">
<?php foreach ($notes as $note): ?>
        <div class="pk-note" onclick="window.location.href='view_notice.php?title=<?= urlencode($note['title']) ?>'">
            <h2 class="pk-note__title"><?= htmlspecialchars($note['title']) ?></h2>
            <p class="pk-note__content"><?= nl2br(htmlspecialchars($note['content'])) ?></p>
            <small class="pk-note__timestamp">Создано: <?= htmlspecialchars($note['created_at']) ?>
              <?php if (!empty($note['updated_at'])): ?>
              <br>
              <span style="font-size:11px">Изменено в последний раз: <?= htmlspecialchars($note['updated_at']) ?></span>
            <?php endif; ?>
            </small>
        </div>
<?php endforeach; ?>
    </div>
        <button data-redirect-b="add_note.php" class="pk-btn-add-note"><i class="material-icons">add</i></button>
    </div>
    <div class="pk-sidebar" id="sidebar">
        <div class="pk-sidebar__content">
            <button class="pk-sidebar__close-btn" id="close-sidebar"><i class="material-icons">close</i></button>
            <ul class="pk-sidebar__menu">
                <li class="pk-sidebar__item"><a href="#" class="pk-sidebar__link">Главная</a></li>
                <li class="pk-sidebar__item"><a href="#" class="pk-sidebar__link">Информация</a></li>
            </ul>
            <div class="pk-sidebar__content-version" style="position:fixed;bottom:15px;font-size:17px">
                <p class="pk-h3-v">Beta <?= htmlspecialchars($version)?></p>
            </div>
        </div>
    </div>
    
    <?php include 'include/html/footer.php' ?>

    <script src="assets/js/index.js" type="text/javascript" nonce="<?= $nonce ?>"></script>
</body>
</html>