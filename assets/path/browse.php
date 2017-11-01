<?php

$ass->get('/', function () use ($ass) {
    $page = 0;
    $perPage = 16;
    $offset = 0;
    $listPageUp = array();
    $listPageDown = array();
    $maxPage = round(\ass\MAP::getInstance()->count('selfie', array('visible' => 1)) / $perPage);
    if (isset($_GET['p']) && $_GET['p'])
    {
        $page = $_GET['p'];
        if ($page < 0 || $page >= $maxPage || !is_numeric($page))
            $page = 0;
        $offset = $page * $perPage;
    }
    if ($page != 0){
        $listPageDown[0] = 0;
    }
    if ($maxPage - 1 != $page && $maxPage > 0){
        $listPageUp[$maxPage - 1] = $maxPage - 1;
    }
    for($i = 1; $i < 4; $i++) {
        if ($page - $i >= 0) {
            $listPageDown[$page - $i] = $page - $i;
        }
        if ($page + $i < $maxPage) {
            $listPageUp[$page + $i] = $page + $i;
        }
    }
    sort($listPageDown);
    sort($listPageUp);
    $selfies = \ass\MAP::getInstance()->findAll('selfie', array('visible' => 1), array('dateCreated', 'DESC'), array($offset, $perPage));
    $ass->render('browser', array('selfies' => $selfies, 'current' => $page, 'listPageDown' => $listPageDown, 'listPageUp' => $listPageUp));
});