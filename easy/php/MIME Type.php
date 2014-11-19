<?php
/**
 * Auto-generated code below aims at helping you parse
 * the standard input according to the problem statement.
 **/

fscanf(STDIN, "%d",
    $N // Number of elements which make up the association table.
);
fscanf(STDIN, "%d",
    $Q // Number Q of file names to be analyzed.
);

$mimes = array();
for ($i = 0; $i < $N; $i++) {

    fscanf(STDIN, "%s %s",
        $EXT, // file extension
        $MT // MIME type.
    );
    $mimes[strtolower($EXT)] = $MT;
}

for ($i = 0; $i < $Q; $i++) {
    
    $FNAME = stream_get_line(STDIN, 512, "\n"); // One file name per line.
    $pos = strrpos($FNAME, '.');
    
    if ($pos !== false) {
        $pos++;
        $f_ext = strtolower(substr($FNAME, $pos, strlen($FNAME)));
      
        if(isset($mimes[$f_ext])) {
            
            echo($mimes[$f_ext]."\n");
            
        } else {
            
            echo("UNKNOWN\n");
            
        }
    } else {
        echo("UNKNOWN\n"); // For each of the Q filenames, display on a line the corresponding MIME type. If there is no corresponding type, then display UNKNOWN.        
    }
}

// Write an action using echo(). DON'T FORGET THE TRAILING \n
// To debug (equivalent to var_dump): error_log(var_export($var, true));


?>