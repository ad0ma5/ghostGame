<?php
/*
 *
 * */
define (PATH_TO_DICTIONARY, "../../../firstClarity/word.lst");
session_start();
include "GhostGame.php";
$output = array();

/*
 * User session resolution
 * */
if(empty($_GET['user']) && empty($_SESSION['user'])){
    $output['error'] = 'no user registered';
}elseif(empty($_SESSION['user'])){
    $_SESSION['user'] = $_GET['user'];
    $_SESSION['ghost'] = '';
    $_SESSION['ai_ghost'] = '';

    $output['user'] = $_SESSION['user'];
    $output['ghost'] = $_SESSION['ghost'];
    $output['ai_ghost'] = $_SESSION['ai_ghost'];
}elseif(isset($_GET['new'])){
    $_SESSION['user'] = '';
    session_destroy();
    session_start();
}else{
    $output['user'] = $_SESSION['user'];
    $output['ghost'] = $_SESSION['ghost'];
    $output['ai_ghost'] = $_SESSION['ai_ghost'];
}

/*
 * main game
 * */
if(empty($output['error']) && !empty($_GET['word'])){
    //word with next letter
    $word_in = $_GET['word'];
    $config = array("data_path"=>PATH_TO_DICTIONARY);
    $game = new GhostGame($config);
    $word_out = $game->play($word_in);
    $output = array_merge($output,$word_out);
}
echo json_encode($output);
