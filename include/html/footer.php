<?php
$file = 'index.php';

$lastModified = filemtime($file);

$pageChanges = "Последнее обновление страницы: " . date("d.m.Y H:i:s", $lastModified);
?>   
    <footer class="pk-footer">

        <div class="pk-footer__container">
            <p class="pk-footer__copyright">© <?php echo date("Y"); ?></p>
            <p class="pk-footer__changes"><?= $pageChanges; ?></p>
        </div>
    </footer>