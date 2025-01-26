<?php
date_default_timezone_set('Europe/Kiev');

include 'include/connect.php';

if (!isset($_COOKIE['user_id'])) {
    header('Location: index.php');
    exit;
} else {
    $userId = $_COOKIE['user_id'];
}

$title = null;
$note = null;

if (isset($_GET['title'])) {
    $title = urldecode($_GET['title']);
    
    $stmt = $pdo->prepare("SELECT * FROM notes WHERE title = :title AND user_id = :user_id LIMIT 1");
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();
    $note = $stmt->fetch();

    if (!$note) {
        echo "Note not found or does not belong to you!";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newTitle = $_POST['title'];
    $newContent = $_POST['content'];

    if (!empty($newTitle)) {
        $stmt = $pdo->prepare("UPDATE notes SET title = :title, content = :content, updated_at = :updated_at WHERE title = :old_title AND user_id = :user_id");
        
        $update_at = date('Y-m-d H:i:s');

        $stmt->bindParam(':title', $newTitle);
        $stmt->bindParam(':updated_at', $update_at);
        $stmt->bindParam(':content', $newContent);
        $stmt->bindParam(':old_title', $title);
        $stmt->bindParam(':user_id', $userId);

        $stmt->execute();

        header("Location: index.php");
        exit;
    } else {
        echo "Fill in all the fields!";
    }
}

if (isset($_GET['delete']) && $_GET['delete'] == '1' && isset($_GET['title'])) {
    $title = urldecode($_GET['title']);
    
    $stmt = $pdo->prepare("DELETE FROM notes WHERE title = :title AND user_id = :user_id");
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        header('Location: index.php');
        exit;
    } else {
        echo "Error deleting note.";
    }
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Просмотр Заметки</title>
    <link rel="stylesheet" href="assets/css/index.css" type="text/css" media="all">
  </head>
  <body>
    <header class="pk-header">
        <div class="pk-header__container">
            <h3 class="pk-header__title-content"><?= htmlspecialchars($note['title'] ?? 'Untitled') ?></h3>
        </div>
    </header>
    <main class="pk-main-view-note">
      <form id="editForm" method="POST" class="pk-note-form">
        <div class="pk-form-group">
          <input type="text" name="title" id="title" class="pk-form-input" maxlength="30" value="<?= htmlspecialchars($note['title'] ?? '') ?>" required>
        </div>
        <div class="pk-form-group">
          <textarea name="content" id="content" class="pk-form-textarea" maxlength="500"><?= htmlspecialchars($note['content'] ?? '') ?></textarea>
        </div>
        <button type="submit" id="saveBtn" class="pk-btn-submit" disabled><p class="pk-btn-submit-content">Сохранить изменения</p></button>
      </form>
     <form method="GET" action="" style="margin-top: 20px;">
      <input type="hidden" name="delete" value="1">
      <input type="hidden" name="title" value="<?= htmlspecialchars($note['title']) ?>">
      <button type="submit" class="pk-btn-submit"><p class="pk-btn-submit-content">Удалить заметку</p></button>
    </form>
    </main>
    <script src="assets/js/index.js" type="text/javascript"></script>
    <script type="text/javascript">
      const titleInput = document.getElementById('title');
      const contentInput = document.getElementById('content');
      const saveBtn = document.getElementById('saveBtn');

      let originalTitle = titleInput.value;
      let originalContent = contentInput.value;

      function checkChanges() {
        if (titleInput.value !== originalTitle || contentInput.value !== originalContent) {
          saveBtn.disabled = false;
        } else {
          saveBtn.disabled = true;
        }
      }

      titleInput.addEventListener('input', checkChanges);
      contentInput.addEventListener('input', checkChanges);

      const inputTitle = document.getElementById('title');
      const ElementTitle = document.querySelector('.pk-header__title-content');
    
      inputTitle.addEventListener('input', function() {
        if (!inputTitle.value) {
          ElementTitle.textContent = '<?= htmlspecialchars($note['title'] ?? 'Untitled') ?>';
        } else {
          ElementTitle.textContent = inputTitle.value;
        }
      });
    
    </script>
  </body>
</html>