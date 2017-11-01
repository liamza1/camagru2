<?php

$ass->get('/selfie', function () use ($ass) {
    $ass->access('onlyMember');
    $icons = scandir("img/icon/");
    unset($icons[0], $icons[1]);
    $selfie = \ass\MAP::getInstance()->findAll('selfie', array('people' => unserialize($_SESSION['people'])->getId(), 'visible' => 1), array('dateCreated', 'DESC'), array(0, 10));
    $ass->render('/selfie', array('icons' => $icons, 'selfies' => $selfie, 'session' => $_SESSION));
});

$ass->post('/selfie', function () use ($ass) {
    $ass->access('onlyMember');
    if (isset($_POST['img']))
    {
        if (isset($_POST['img-0-name'])) {
            $Selfie = \ass\mech\Selfie::add(unserialize($_SESSION['people']));
            if ($Selfie instanceof \ass\mech\Selfie) {
                try{
                    new \ass\mech\Image($_POST['img'], $_POST, $Selfie->getName());
                } catch (Exception $e) {
                    $ass->error("Bad file type");
                    $ass->redirect('/selfie');
                }
                $ass->success("Your image has been saved");
                $ass->redirect('selfie');
            }
        }
        $ass->error("You must have at least one filter");
        $ass->redirect('/selfie');
    }
    $ass->error("An error has occurred");
    $ass->redirect('/selfie');
});

$ass->get('/selfie/delete', function () use ($ass) {
    $ass->access('onlyMember');
    if (isset($_GET['id'])){
        $error = \ass\mech\Selfie::remove($_GET['id'], unserialize($_SESSION['people'])->getId());
        if ($error){
            $ass->success("Your image has been deleted");
            $ass->redirect('/selfie');
        }
    }
    $ass->error("An error has occurred");
    $ass->redirect('/selfie');
});

$ass->get('/selfie/like', function () use ($ass) {
    $ass->access('onlyMember');
    if (isset($_GET['id'])){
        echo $_GET['id'];
        $like = new \ass\mech\Likes();
        $like->setPeople(unserialize($_SESSION['people'])->getId());
        $like->setSelfie($_GET['id']);
        $ret = $like->like();
        if ($ret == 1){
            $ass->success("You liked the Selfie");
        } elseif ($ret == 2){
            $ass->success("You Unliked the Selfie");
        }
        if (isset($_GET['callback']) && !empty($_GET['callback']))
            $ass->redirect('/'.$_GET['callback']);
        else
            $ass->redirect('/selfie/show?id='.$_GET['id']);
    }
    $ass->error("An error has occurred");
    $ass->redirect('/selfie');
});

$ass->get('/selfie/show', function () use ($ass) {
    if (isset($_GET['id'])) {
        $selfie = \ass\MAP::getInstance()->findOne('selfie', array('name' => $_GET['id'], 'visible' => 1));
        if ($selfie instanceof \ass\mech\Selfie) {
            $people = \ass\MAP::getInstance()->findOne('people', array('id' => $selfie->getPeople()));
            $isLike = \ass\MAP::getInstance()->findOne('likes', array('people' => $people->getId(), 'selfie' => $selfie->getId())) instanceof \ass\mech\Likes ? true : false;
            $countLike = \ass\MAP::getInstance()->count('likes', array('selfie' => $selfie->getId()));
            if ($people instanceof \ass\mech\Users) {
                $comments = \ass\MAP::getInstance()->findAll('comment', array('selfie' => $selfie->getId()), array('id', 'DESC'), array(0, 10));
                foreach($comments as $k => $v){
                    $author = \ass\MAP::getInstance()->findOne('people', array('id' => $v['people']));
                    $avatar = file_get_contents('http://avatar.teub.es/api?size=100&q='.$author->getPseudo());
                    $avatar = json_decode($avatar);
                    $comments[$k]['display'] = "<div class='avatar'><img src='".$avatar->base64."'></div>
                        <h4>".$author->getPseudo()."</h4><div class='message'>".$v['message']."</div>";
                }
                $ass->render('show', array('pseudo' => $people->getPseudo(), 'name' => $selfie->getName(), 'comments' => $comments, 'isLike' => $isLike, 'countLike' => $countLike));
            }
        }
    } else {
        $ass->error("An error has occurred");
        $ass->redirect('/browse');
    }
});

$ass->post('/selfie/show', function () use ($ass) {
    $ass->access('onlyMember');
    if (isset($_GET['id']) && isset($_POST['message'])) {
        $selfie = \ass\MAP::getInstance()->findOne('selfie', array('name' => $_GET['id'], 'visible' => 1));
        if ($selfie instanceof \ass\mech\Selfie) {
            $comment = new \ass\mech\Comment();
            $comment->setMessage($_POST['message']);
            $error = $comment->checkMessage();
            if ($error) {
                $ass->error($error);
                $ass->redirect('/selfie/show?id='.$_GET['id']);
            } else {
                $comment->setSelfie($selfie->getId());
                $comment->setPeople(unserialize($_SESSION['people'])->getId());
                $comment->send();
                $author = \ass\MAP::getInstance()->findOne('people', array('id' => $comment->getPeople()));
                $people = \ass\MAP::getInstance()->findOne('people', array('id' => $selfie->getPeople()));
                mail($people->getEmail(), 'A new comment', 'Hello'.$author->getPseudo().',<br><br>'.$author->getPseudo().'
has left a comment on one of your selfies <a href="https://xxxx/selfie/show?id='.$selfie->getName().'">See the comment</a><br><br>A bient&ocirc;t :)');
                $ass->success("Your comment has been posted");
                $ass->redirect('/selfie/show?id='.$_GET['id']);
            }
        }
    } else {
        $ass->error("An error has occurred");
        $ass->redirect('/selfie/show?id='.$_GET['id']);
    }
});