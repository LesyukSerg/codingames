<? fscanf(STDIN, "%d %d %d %d", $x, $y, $X, $Y);
    while (1) {
        if ($Y < $y) { ?>S<? $Y++;}
        elseif ($Y > $y) { ?>N<? $Y--;}
        if ($X < $x) { ?>E<? $X++;}
        elseif ($X > $x) { ?>W<? $X--;} ?>
<? }