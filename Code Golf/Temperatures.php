<?fgets(STDIN);$t=explode(' ',trim(fgets(STDIN)));sort($t);$m=999;foreach($t as $p)$m=abs($p)<=abs($m)?$p:$m;echo $m?$m:0;