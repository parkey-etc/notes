<?php
try {
  $dns = 'sqlite:' . __DIR__ . '/notes.db';
  $username = null; // Для SQLite не нужно указывать имя пользователя
  $password = null;  // Для SQLite не нужно указывать пароль

  // Создаем объект PDO
  $pdo = new PDO($dns, $username, $password);

  // Устанавливаем параметры для PDO
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

} catch (PDOException $e) {
  // Отлавливаем ошибки подключения
  echo "Ошибка: " . $e->getMessage();
}
