<?php
/*
 * GhostGame Class
 * */
class GhostGame{
    private $data_path;
    private $error = FALSE;
    private $error_msg;
    private $data;
    private $min_word = 4;
    /*
     * Constructor
     * */
    public function __construct($config = NULL){
        if(!empty($config)){
            if(!empty($config['data_path'])){
                $this->data_path = $config['data_path'];
            }
        }
    }
    /*
     * loadData - loading word list from file
     * */
    public function loadData(){
        if(empty($this->data_path)){
            $this->setError("missing data path");
        }else{
            if(!file_exists($this->data_path)){
                $this->setError("no such file exists at ".$this->data_path);
            }else{
                $this->data = file_get_contents($this->data_path);
            }
        }
    }
    /*
     * Ghost game step
     * */
    public function play($word_in){
        $output = array();
        $this->loadData();
        //if any errors initialising
        if($this->getError()){
            $output['output'] = $this->getErrorMsg();
            $output['status'] = 'nok';
        }else{
            //find array of words from the list fitting requested beginning of the word
            $words_arr = $this->findWords($word_in);
            //
            if(isset($_GET['list_words'])){echo "<pre>".print_r($words_arr,true)."</pre>";die;}
            //no words available -> fail
            if(count($words_arr) == 0){
                $output['output'] =  "You lost. No such word in dictionary '".$word_in."'";
                $output['word'] =  $word_in;
                $output['status'] =  'nok';
                $output['ghost'] = $this->addGHOST('ghost');
            }else{//words available
                // if users word is already finished?
                if($this->isWordFinished($word_in, $words_arr)){
                    $output['output'] =  "You lost. You finished the word '".$word_in."'";
                    $output['word'] =  $word_in;
                    $output['status'] = 'nok';
                    $output['ghost'] = $this->addGHOST('ghost');
                    //$output['key'] = $key;
                }else{//user did ot finished the word lets try finding wining or longest word
                    //find winning word
                    $result_word = $this->findWinWord($words_arr, $word_in);
                    //and get next letter
                    $next_letter = $this->getNextLetter($word_in,$result_word);
                    //just in case doublecheck if next word is not finished
                    if($this->isWordFinished($word_in.$next_letter, $words_arr)){
                        $output['output'] =  "AI lost, finished word ".$word_in.$next_letter." by choosing '".$result_word."'";
                        $output['word'] =  $result_word;
                        $output['status'] = 'nok';
                        $output['ai_ghost'] = $this->addGHOST('ai_ghost');
                    }else{
                        // if no available next letter
                        if(empty($next_letter)){
                                $output['output'] =  "You lost. Finished word '".$result_word."'";
                                $output['word'] =  $word_in;
                                $output['status'] = 'nok';
                                $output['ghost'] = $this->addGHOST('ghost');

                        }else{//we pass next letter and let user play
                                $output['output'] =  " result ".$result_word." next=".$next_letter." ";
                                $output['word']   =  $word_in.$next_letter;
                                $output['status'] =  'ok';
                        }
                    }
                }
            }//words available
        }//no errors initialising
        return $output;
    }
    /*
     * resolveGHOST - add letter to the player's ghost value
     * */
    public function addGHOST($player){
        $ghost = $_SESSION[$player];
        $count_loses = strlen($ghost);
        $_SESSION[$player] = substr("ghost",0,$count_loses+1);
        //$output[$player] = $_SESSION[$player];
        return $_SESSION[$player];
    }
    /*
     * findWinWord - method finding the possible words out of list
     * */
    public function findWinWord($words = array(), $word_in = NULL){
        $word_return = "";
        //in case no win we play longest
        $longest_word = "";
        if($words){
            // we shuffle results arr for randomnes
            shuffle ( $words );
            foreach($words as $word){
                //echo $word.' ';
                //if word is of length 4+
              if(strlen($word) >= $this->min_word)
                if($this->isOdd($word)){
                    //check if next word will not be finished
                    $next_letter = $this->getNextLetter($word_in, $word);
                    if(!$this->isWordFinished($word_in.$next_letter, $words)){
                        $word_return = $word;
                        return $word_return;
                    }
                }else{
                    if( strlen($longest_word) < strlen($word) ){
                        $longest_word = $word;
                    }
                }
            }
        }
        // if winning word was not found returning longest possible loosing word
        return $longest_word;
    }
    /*
     * getNextLetter - extract next letter from wining word
     * */
    public function getNextLetter($word_in, $word_win){
        $word_length = strlen($word_in);
        return substr(substr($word_win, $word_length),0,1);
    }
    /*
     * isOdd - winning condidtion: word to be even if the player starts the game
     * */
    private function isOdd($word){
        $number = strlen($word);
        //echo ' number = '.$number.' ';
        return $number % 2 != 0;
    }
    /*
     * isWordFinished - if the word already exist in dictionary
     * */
    public function isWordFinished($word, $words_arr){
        $key = array_search($word, $words_arr);
        if(strlen($word) >= $this->min_word)
        if($key !== FALSE) {
            return TRUE;
        }
        return FALSE;
    }
    /*
     * findWords - find possible words using pattern
     * */
    public function findWords($word){

        // escape special characters in the query
        $pattern = preg_quote($word, '/');
        // finalise the regular expression, matching the whole line
        $pattern = "/^$pattern.*\$/m";
        // search, and store all matching occurences in $matches
        if(preg_match_all($pattern, $this->data, $matches)){
            return $matches[0];
        }else{
           //No matches found
            return NULL;
        }
    }
    /*
     * error methods
     * */
    private function setError($msg){
        $this->error = TRUE;
        $this->error_msg = $msg;
    }
    public function getError(){
        return $this->error;
    }
    public function getErrorMsg(){
        return $this->error_msg;
    }
}
