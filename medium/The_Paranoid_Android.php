<?php
/**
 * Auto-generated code below aims at helping you parse
 * the standard input according to the problem statement.
 **/

fscanf(STDIN, "%d %d %d %d %d %d %d %d",
    $nbFloors, // number of floors
    $width, // width of the area
    $nbRounds, // maximum number of rounds
    $exitFloor, // floor on which the exit is found
    $exitPos, // position of the exit on its floor
    $nbTotalClones, // number of generated clones
    $nbAdditionalElevators, // ignore (always zero)
    $nbElevators // number of elevators
);

$ELEVATOR = array();
for ($i = 0; $i < $nbElevators; $i++)
{
    fscanf(STDIN, "%d %d",
        $elevatorFloor, // floor on which this elevator is found
        $elevatorPos // position of the elevator on its floor
    );
    $ELEVATOR[$elevatorFloor] = $elevatorPos;
}
$ELEVATOR[$exitFloor] = $exitPos;

// game loop
$flagFLOOR = false;
while (TRUE)
{
    fscanf(STDIN, "%d %d %s",
        $cloneFloor, // floor of the leading clone
        $clonePos, // position of the leading clone on its floor
        $direction // direction of the leading clone: LEFT or RIGHT
    );
    
    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));
    //error_log(var_export($clonePos.' '.$cloneFloor.' '.$ELEVATOR[$cloneFloor].' '.$flagFLOOR, true));    
        
    if( ($ELEVATOR[$cloneFloor] < $clonePos && $clonePos > $OLD_POS) || ($ELEVATOR[$cloneFloor] > $clonePos && $clonePos < $OLD_POS) )
    {
        if($flagFLOOR !== $cloneFloor) {
            echo("BLOCK\n");
            $flagFLOOR = $cloneFloor;
        } else {
            echo("WAIT\n");
        }
    } else {
        echo("WAIT\n");
    }
    
    $OLD_POS = $clonePos;
}
