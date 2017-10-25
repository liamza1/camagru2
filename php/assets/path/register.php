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
?>