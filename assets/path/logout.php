<?php
$ass->get('/logout', function () use ($ass) {
    $ass->access('onlyMember');
    unset($_SESSION['people']);
    $ass->redirect('/');
});