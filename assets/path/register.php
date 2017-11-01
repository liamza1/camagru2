<?php

$ass->get('/register', function () use ($ass){
    $ass->access('onlyGuest');
  if (isset($_GET['key'])){
      if(\ass\mech\users::validateEmail($_GET['key'])){
          $ass->success("your account has been validated");
          $ass->redirect('/login');
      }else{
          $ass->error("an error occurred");
          $ass->redirect('/register');
      }
  }
    $ass->render('register', array());
});

$ass->post('/register', function () use ($ass){
$ass->acces('onlyGuest');
$people = new \ass\mech\Users();
        if (isset($_POST['email']))
            $people->setEmail($_POST['email']);
        if (isset($_POST['pseudo']))
            $people->setPseudo($_POST['pseudo']);
        if (isset($_POST['password']))
            $people->setPassword($_POST['password']);
        $error = $people->register();
        if (!isset($error['password']) && $_POST['password'] != $_POST['password2'])
            $error['password'] = 'Passwords are not the same';
        if (empty($error)){
            mail($people->getEmail(), 'confirmation', 'Hello '.$people->getPseudo().',<br><br> Pour confirmer ton inscription, clique sur ce lien : <a href="https://xxxx/register?key='.$people->getTokenValidated().'">https://xxx/register?key='.$people->getTokenValidated().'</a><br><br>A bient&ocirc;t :)');
            $ass->render('register_done', array('email' => $people->getEmail()));
        }
        else
            $ass->render('register', array('post' => $_POST, 'error' => $error));
    });
?>