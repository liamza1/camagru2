<?php

$ass->get('/login', function () use ($ass) {
    $ass->access('onlyGuest');
    $ass->render('login', array());
});

$ass->post('/login', function () use ($ass) {
    $ass->access('onlyGuest');
    $people = \ass\mech\Users::login($_POST['pseudo'], $_POST['password']);
    if ($people instanceof \ass\mech\Users) {
        $_SESSION['people'] = serialize($people);
        $ass->redirect('/');
    }
    if ($people == 1)
        $error = 'Your email address has not been validated yet';
    else
        $error = 'Unfortunately no account has been found!';
    $ass->render('login', array('error' => $error));
});

?>