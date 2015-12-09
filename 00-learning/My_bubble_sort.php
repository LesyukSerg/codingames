<?php
    /**
     * My Bubble Sort implementation.
     * The expected result for the integers 5, 4, 9, 2, 7 is the following lines:
     * 45927
     * 45297
     * 45279
     * 42579
     * 24579
     **/

    fscanf(STDIN, "%d", $n); // the amount of integers to sort

    $numbers = [];

    for ($i = 0; $i < $n; $i++) {
        fscanf(STDIN, "%d", $numbers[]); // an integer (to begin you have to store all the integers into an array)
    }

    for ($i = 0; $i < $n; $i++) {
        for ($i2 = 0; $i2 < $n-1; $i2++) {
            if($numbers[$i2] > $numbers[$i2+1]){
                list($numbers[$i2], $numbers[$i2+1]) = array($numbers[$i2+1], $numbers[$i2]);
                echo implode('',$numbers)."\n";
            }
        }
    }

    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));

    echo("\n"); // Use this function to dump your array after a swapping operation (ie. 45927 should be the first line)
