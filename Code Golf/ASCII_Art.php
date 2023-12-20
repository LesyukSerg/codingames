<?$N=fgets(STDIN);$H=fgets(STDIN);$t=strtoupper(trim(fgets(STDIN)));for($i=0;$i<$H;$i++){$L=fgets(STDIN);for($k=0;$k<strlen($t);$k++){$P=ord($t[$k])-65;$P=$P<0?26:$P;echo substr($L,$P*$N,$N);}
?>

<?
}