<?list($x,$y,$X,$Y)=fgetcsv(STDIN,0,' ');while(1){echo$Y++<$y?"S":"";echo$X++<$x?"E":(--$X>$x?"W":"");?>

<?}