<?php

require('model.php');

$mydb = dbconnect();

$json = file_get_contents('php://input');
if(sizeof($_POST) or $json or (isset($argv[1]) and isset($argv[2]))){
    if($json){
        if(!is_json($json))
            apidie('Invalid POST JSON', 422);
        $_POST = json_decode($json, true);
    }
    else if(isset($argv[1]) and isset($argv[2])){
        $_POST['id'] = $argv[1];

        unset($argv[0]);
        unset($argv[1]);
        $_POST['comments'] = implode(' ', $argv);

        $_POST['password'] = '720DF6C2482218518FA20FDC52D4DED7ECC043AB';
    }

    foreach(['password', 'id', 'comments'] as $key){
        if(missing_post($key) or !$key)
            apidie('Missing key/value for "'.$key.'"', 422);
    }

    if(strtoupper($_POST['password']) != '720DF6C2482218518FA20FDC52D4DED7ECC043AB')
        apidie('Invalid password', 401);

    if(!is_numeric($_POST['id']))
        apidie('Invalid id', 422);

    if($error = append_user_comments($_POST['id'], $_POST['comments']))
        apidie('Could not update database: '.$error, 500);

    apidie('OK', 200);
}
else if(isset($_GET['id'])){
    $user = get_user_by_id($_GET['id']);
    require('view.php');
}

apidie('No such user (3)', 404);