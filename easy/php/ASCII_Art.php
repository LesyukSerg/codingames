<?php
// Read inputs from STDIN. Print outputs to STDOUT.

fscanf(STDIN, "%d", $LetterNumber);
fscanf(STDIN, "%d", $HeightLetter);

$Text = stream_get_line(STDIN, 256, "\n");
$alpha = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

// To debug (equivalent to var_dump): error_log(var_export($var, true));

$Text = strtoupper($Text);

for ($i = 0; $i < $HeightLetter; $i++) {
    $word_line = '';
    $LINE = stream_get_line(STDIN, 1024, "\n");
    
    for ($k=0; $k<strlen($Text); $k++) {
        $pos = strpos($alpha, $Text[$k]);
        //$pos = array_search($W[$k], $alpha);
        
        if($pos === false) $pos = 26;
        
        echo substr($LINE, $pos*$LetterNumber, $LetterNumber);
        
    }
    
    echo "\n";
}
?>