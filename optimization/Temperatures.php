<? fscanf(STDIN,"%d",$N);$T=stream_get_line(STDIN,256,"\n");if($T){$T=explode(' ',$T);$p=9999;$m=-$p;foreach($T as $t){if($t<0){if($m<$t)$m=$t;}elseif($p>$t)$p=$t;}if(-$m < $p)die("$m\n");else die("$p\n");}die("0\n");

