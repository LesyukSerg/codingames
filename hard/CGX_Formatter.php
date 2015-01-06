<?php

fscanf(STDIN, "%d",
    $N
);

$TEXT = '';
for ($i = 0; $i < $N; $i++) {
    $TEXT .= stream_get_line(STDIN, 1000);
}

// Write an action using echo(). DON'T FORGET THE TRAILING \n
// To debug (equivalent to var_dump): error_log(var_export($var, true));

//$TEXT = "		'	ke	y		'	=	 '	va	 lue	  '		";

$TEXT = str_replace("\n", '', $TEXT);

$TN = 0;
$OFF = 0;

$CNT = strlen($TEXT);

for($i=0; $i<$CNT; $i++){
    $OUT = $TEXT[$i];
    
    //error_log(var_export($OUT, true));
    if($OUT == "'") {
        $OFF = abs($OFF-1);
    }
    
    if(!$OFF) {
        if($OUT == '(') {
            $TN++;
            
            $j = searchelem($TEXT, $i, -1);
            
            if(isset($TEXT[$j]) && ($TEXT[$j] == '=' || $TEXT[$j] == ')') ) {
                echo "\n";
                
                tabs($TN-1);
            }
         
            echo "(\n";
            
            $j = searchelem($TEXT, $i, 1);
            
            if($TEXT[$j] != ')') {
                tabs($TN);
            }
        }
        elseif($OUT == ')') {
            $TN--;
            
            $j = searchelem($TEXT, $i, -1);
            
            if($TEXT[$j] != '(') {
                echo "\n";
            }
            
            tabs($TN);
            echo ')';
        }
        elseif($OUT == ';') {
            echo ";\n";
            
            tabs($TN);
        }
        elseif($OUT == ' ' || $OUT == "\t" || $OUT == "\n") {
            echo ''; #do nothing
        }
        else {
            echo $OUT;
        }
    }
    else {
        echo $OUT;
    }
}






function tabs($TN)
{
    for($t=0; $t<$TN; $t++) {
        echo '    ';
    }
}

function searchelem($TEXT, $i, $k)
{
    $j = $i+$k;
    while(isset($TEXT[$j]) && ($TEXT[$j] == ' ' || $TEXT[$j] == "\t") ) $j += $k;
    
    return $j;
}