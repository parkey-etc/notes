<?php
date_default_timezone_set('Europe/Kiev');

include 'include/connect.php';

if (!isset($_COOKIE['user_id'])) {
    header('Location: index.php');
} else {
    $userId = $_COOKIE['user_id'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    if (!empty($title)) {
        try {
            $currentTime = date('Y-m-d H:i:s');
            $sql = "INSERT INTO notes (title, content, created_at, updated_at, user_id) VALUES (:title, :content, :created_at, :updated_at, :user_id)";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':created_at', $currentTime);
            $stmt->bindParam(':updated_at', $currentTime);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();

            header('Location: index.php');
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Fill in all fields!']);
    }
    exit;
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заметки</title>
    <link rel="stylesheet" href="assets/css/index.css" type="text/css" media="all">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  </head>
  <body>
    <header class="pk-header">
        <div class="pk-header__container">
            <h3 class="pk-header__title-content">Заметки</h3>
        </div>
    </header>
    <main class="pk-main-add-note">
      <form method="POST" class="pk-note-form">
        <div class="pk-form-group">
          <input type="text" id="title" name="title" class="pk-form-input" maxlength="30" required placeholder="Заголовок..">
        </div>
        <div class="pk-form-group">
          <textarea id="content" name="content" class="pk-form-textarea" maxlength="500" placeholder="Описание..."></textarea>
        </div>
        <button type="submit" class="pk-btn-submit"><p class="pk-btn-submit-content">Сохранить</p></button>
      </form>
    </main>
  <?php include 'include/html/footer.php' ?>
  <script src="assets/js/index.js" type="text/javascript"></script>
  <script type="text/javascript">
    const inputTitle = document.getElementById('title');
    const ElementTitle = document.querySelector('.pk-header__title-content');
    
    inputTitle.addEventListener('input', function() {
      if (!inputTitle.value) {
        ElementTitle.textContent = 'Заметки';
      } else {
        ElementTitle.textContent = inputTitle.value;
      }
    });
  </script>
  </body>
</html>