<?php
/**
 * The code below will read all the game information for you.
 * On each game turn, information will be available on the standard input, you will be sent:
 * -> the total number of visible enemies
 * -> for each enemy, its name and distance from you
 * The system will wait for you to write an enemy name on the standard output.
 * Once you have designated a target:
 * -> the cannon will shoot
 * -> the enemies will move
 * -> new info will be available for you to read on the standard input.
 **/


// game loop
while (TRUE)
{
    $ENEMYS = array();
    fscanf(STDIN, "%d",
        $count // The number of current enemy ships within range
    );
    
    for ($i = 0; $i < $count; $i++) {
        fscanf(STDIN, "%s %d",
            $enemy, // The name of this enemy
            $dist // The distance to your cannon of this enemy
        );
        
        $ENEMYS[$dist] = $enemy;
    }
    
    ksort($ENEMYS);
    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));

    echo current($ENEMYS)."\n"; // The name of the most threatening enemy (HotDroid is just one example)
}