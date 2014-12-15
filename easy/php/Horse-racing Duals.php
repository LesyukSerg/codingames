<?php

fscanf(STDIN, "%d", $N);

$H = array();
for ($i = 0; $i < $N; $i++) {
    fscanf(STDIN, "%d", $H[]);
}
sort($H);

$D = array();
$N--;
for ($i=0; $i<$N; $i++) {
    $D[] = abs($H[$i] - $H[$i+1]);
}

echo(min($D)."\n");
