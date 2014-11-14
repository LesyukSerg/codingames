<?php
/**
 * Auto-generated code below aims at helping you parse
 * the standard input according to the problem statement.
 **/

fscanf(STDIN, "%d",
    $N
);
$WORDS = array();
for ($i = 0; $i < $N; $i++){
    $WORDS[] = stream_get_line(STDIN, 30, "\n");
}
$LETTERS = stream_get_line(STDIN, 7, "\n");
//error_log(var_export($WORDS, true));
error_log(var_export($LETTERS, true));

$COSTS = array(
    'e' => 1, 'a' => 1, 'i' => 1, 'o' => 1, 'n' => 1, 'r' => 1, 't' => 1, 'l' => 1, 's' => 1, 'u' => 1,
    'd' => 2, 'g' => 2,
    'b' => 3, 'c' => 3, 'm' => 3, 'p' => 3,
    'f' => 4, 'h' => 4, 'v' => 4, 'w' => 4, 'y' => 4,
    'k' => 5,
    'j' => 8, 'x' => 8,
    'q' => 10, 'z' => 10
);


# words to fit -------------------------------------
foreach($WORDS as $k => $word) {
    //error_log(var_export($word, true));
    $cnt = strlen($word);
    $LW = $LETTERS;
    
    for($i=0; $i<$cnt; $i++) {
        //error_log(var_export(strstr($LETTERS, $word[$i]), true));
        $SS = strpos($LW, $word[$i]);
        if($SS  === false) {
            break;
        } else {
            $LW[$SS] = ' '; # use letter only one time
        }
    }
    
    if($i != $cnt) { # if not all letters exist word is not fit
        //error_log(var_export(strlen($word).' - '.$i, true));
        unset($WORDS[$k]);
    }
}
//error_log(var_export($WORDS, true));

# calculate cost -------------------------------------
$W_COST = array();
foreach($WORDS as $k => $word) {
    $cost = 0;
    
    $cnt = strlen($word);
    
    for($i=0; $i<$cnt; $i++) {
        $cost += $COSTS[$word[$i]];
    }
    
    if(empty($W_COST[$cost])) # if word with same cost isn't exist
        $W_COST[$cost] = $word;
}
krsort($W_COST);
error_log(var_export($W_COST, true));

// Write an action using echo(). DON'T FORGET THE TRAILING \n
// To debug (equivalent to var_dump): error_log(var_export($var, true));

echo(current($W_COST)."\n");
