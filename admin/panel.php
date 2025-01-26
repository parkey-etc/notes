<?php
include 'include/connect.php';

// Получение общего количества заметок
$query = $pdo->prepare("SELECT COUNT(*) AS total_notes FROM notes");
$query->execute();
$totalNotes = $query->fetch(PDO::FETCH_ASSOC)['total_notes'];

// Сообщения о статусе
$statusMessage = '';

// Функция для удаления всех заметок
if (isset($_POST['delete_all_notes'])) {
    $deleteQuery = $pdo->prepare("DELETE FROM notes");
    $deleteQuery->execute();
    $statusMessage = 'Все заметки удалены';
}

// Функция для удаления заметки по ID
if (isset($_POST['delete_note'])) {
    $noteId = $_POST['note_id'];
    $deleteNoteQuery = $pdo->prepare("DELETE FROM notes WHERE id = :id");
    $deleteNoteQuery->bindParam(':id', $noteId);
    $deleteNoteQuery->execute();
    $statusMessage = 'Заметка удалена';
}

// Функция для удаления всех заметок по user_id
if (isset($_POST['delete_notes_by_user'])) {
    $userId = $_POST['user_id'];
    $deleteNotesQuery = $pdo->prepare("DELETE FROM notes WHERE user_id = :user_id");
    $deleteNotesQuery->bindParam(':user_id', $userId);
    $deleteNotesQuery->execute();
    $statusMessage = 'Все заметки пользователя удалены';
}

// Путь к файлу VERSION
$versionFile = '/../VERSION';

// Чтение текущей версии
$currentVersion = file_exists($versionFile) ? file_get_contents($versionFile) : 'Не определена';

// Обновление версии
if (isset($_POST['update_version']) && !empty($_POST['new_version'])) {
    $newVersion = $_POST['new_version'];
    file_put_contents($versionFile, $newVersion);
    $currentVersion = $newVersion;
    $statusMessage = 'Версия обновлена!';
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ Панель</title>
</head>
<body>
    <h1>Админ Панель</h1>

    <h2>Общее количество заметок: <?= htmlspecialchars($totalNotes) ?></h2>

    <!-- Вывод статуса -->
    <?php if (!empty($statusMessage)): ?>
        <p><strong><?= htmlspecialchars($statusMessage) ?></strong></p>
    <?php endif; ?>

    <!-- Управление версиями -->
    <h2>Текущая версия: <?= htmlspecialchars($currentVersion) ?></h2>
    <form method="POST">
        <input type="text" name="new_version" placeholder="Новая версия" required>
        <button type="submit" name="update_version">Обновить версию</button>
    </form>

    <!-- Удаление всех заметок -->
    <form method="POST">
        <button type="submit" name="delete_all_notes">Удалить все заметки</button>
    </form>

    <!-- Удаление заметки по ID -->
    <form method="POST">
        <input type="number" name="note_id" placeholder="Введите ID заметки" required>
        <button type="submit" name="delete_note">Удалить заметку по ID</button>
    </form>

    <!-- Удаление всех заметок по user_id -->
    <form method="POST">
        <input type="text" name="user_id" placeholder="Введите user_id" required>
        <button type="submit" name="delete_notes_by_user">Удалить все заметки пользователя</button>
    </form>

    <!-- Добавление категории -->

</body>
</html>