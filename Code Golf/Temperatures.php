<?fgets(STDIN);$t=fgetcsv(STDIN,0,' ');sort($t);$m=999;foreach($t as$p)$m=abs($p)<=abs($m)?$p:$m;echo$m?:0;