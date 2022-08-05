<?php

function apidie($string, $code = 200){
    $string .= "";
    http_response_code($code);
    if(defined('SCRIPT') && SCRIPT)
        throw new Exception($string);
    die($string);
}

function missing_request($field){
    return (!isset($_REQUEST[$field]) or !$_REQUEST[$field]);
}

function missing_post($field){
    return (!isset($_POST[$field]) or !$_POST[$field]);
}

function missing_get($field){
    return (!isset($_GET[$field]) or !$_GET[$field]);
}

function is_json($string) {
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
}

function dbconnect(){
    $mydb = mysqli_connect(getenv('DB_SERVER'), getenv('DB_USER'), getenv('DB_PWD'), getenv('DB_NAME'));
    if(mysqli_connect_error())
        apidie('Could not connect: '.mysqli_connect_error(), 500);
    if(mysqli_error($mydb))
        apidie('Error on connection: '.mysqli_error(), 500);
    return $mydb;
}

function append_user_comments($id, $comments){
    global $mydb;

    $result = mysqli_query($mydb, 'SELECT comments FROM users WHERE id = "'.mysqli_real_escape_string($mydb, $id).'"');
    if(mysqli_error($mydb))
        apidie('DB Error: '.mysqli_error($mydb), 500);
    if(mysqli_num_rows($result) <= 0)
        apidie('No such user (1)', 404);

    $row = mysqli_fetch_object($result);
    $row->comments .= "\n".$comments;

    mysqli_query($mydb, 'UPDATE users SET comments = "'.mysqli_real_escape_string($mydb, $row->comments).'"');
    return mysqli_error($mydb);
}

function contains($haystack, $needle, $case_sensitive = true){
    if(!$case_sensitive)
        return (strpos(strtolower($haystack), strtolower($needle)) !== FALSE);
    return (strpos($haystack, $needle) !== FALSE);
}

function startswith($haystack, $needle, $case_sensitive = true){
    if(!$case_sensitive)
        return (strpos(strtolower($haystack), strtolower($needle)) === 0);
    return (strpos($haystack, $needle) === 0);
}

function get_user_by_id($id){
    global $mydb;

    $result = mysqli_query($mydb, 'SELECT * FROM users WHERE id = "'.mysqli_real_escape_string($mydb, $id).'"');
    if(mysqli_error($mydb))
        apidie('DB Error: '.mysqli_error($mydb), 500);
    if(mysqli_num_rows($result) <= 0)
        apidie('No such user (2)', 404);

    return mysqli_fetch_object($result);
}