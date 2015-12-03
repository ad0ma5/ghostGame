<?php
include_once "config.php";
include_once "GhostGame.php";

class GhostGameTest extends PHPUnit_Framework_TestCase
{

    public function testGame(){
        //init
        $config = array("data_path"=>PATH_TO_DICTIONARY);
        $game = new GhostGame($config);

        //load dictionary
        $game->loadData();
        if($game->getError()) echo $game->getErrorMsg();
        $this->assertEquals(FALSE, $game->getError());

        //find possible words set
        $word_in = "nor";
        $words_arr = $game->findWords($word_in);
        $this->assertEquals(TRUE, count($words_arr) > 0 );

        //find winning/longest word
        $result_word = $game->findWinWord($words_arr);
        $this->assertEquals(TRUE, !empty($result_word) );
        //win word is the right size
        $this->assertEquals(TRUE, strlen($result_word) >= 4 );
        //extract next letter
        $next_letter = $game->getNextLetter($word_in,$result_word);
        $this->assertEquals(1, strlen($next_letter) );

        $word_in = "notegsist";
        $words_arr = $game->findWords($word_in);
        $this->assertEquals(FALSE, count($words_arr) > 0 );

        //$game->readByLine();
        return 0;
    }
}

?>
