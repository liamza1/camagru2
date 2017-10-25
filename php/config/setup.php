<?php
    if (isset($_GET['k'])) {
        require_once('database.php');
        $db = new PDO("mysql:host=127.0.0.1", $DB_USER, $DB_PASSWORD);
        $sql = file_get_contents('struct.sql');
        $qr = $db->exec($sql);
        echo "Done !<br><br>";
    } else {
        echo "Are you sure ? <a href='?k=k'>Remove all data and recreate struct</a>";
    }