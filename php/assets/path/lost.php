<?php
$ass->get('/lost', function () use ($ass) {
    $ass->access('onlyGuest');
    $ass->render('lost', array());
});

$ass->post('/lost', function () use ($ass) {
    $ass->access('onlyGuest');
    if (isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['pseudo']) && !empty($_POST['pseudo'])) {
        $people = \ass\MAP::getInstance()->findOne('people', array('email' => $_POST['email'], 'pseudo' => $_POST['pseudo']));
        if ($people instanceof \ass\mech\Users) {
            $newPassword = $people->changePassword();
            mail($people->getEmail(), 'Forget your password ?', 'Hello'.$people->getPseudo().',<br><br> Here is your new password: '.$newPassword.'<br><br>A bient&ocirc;t :)');
        }
    }
    $ass->success('Your new password has been sent by email');
    $ass->redirect('login');
});