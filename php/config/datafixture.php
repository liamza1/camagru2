<?php
    require_once('database.php');
    $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
echo "Done !<br><br>";