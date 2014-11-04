<?
//$STR = "1 2|1 3|3 4|2 4|2 5|10 11|10 1|10 3|11 4|4 5|4 8|8 12";
//$STR = "1 2|2 5|5 6|5 12|5 13|4 3|2 3|3 7|4 8|8 14|8 15|8 16|4 9|9 10|9 11|9 12|8 16"; //Олег
//$STR = "1 2|1 3|1 4|4 7|4 8|4 9|8 10|8 11|3 5|3 6|6 12|6 13|13 14|13 15"; //Наташа
//$STR = "1 2|2 4|2 8|2 3|8 10|4 6|4 5|6 7|5 8|7 9|9 10"; //Саша
//$STR = "8 4|8 3|8 1|5 3|5 4|5 7|7 6|1 5|1 9|9 1|5 2|2 1|2 8"; //Костя
$STR = "8 4|8 3|8 1|2 8|1 9|1 6|5 2|5 4|5 7|6 7|6 5"; //Костя

function subtree0(&$SUBTREE, $node, &$R)
{ //search in tree node1 and insert node2
    if (count($SUBTREE)) {
        foreach ($SUBTREE as $K => $N) {
            subtree0($SUBTREE[$K], $node, $R);

            if ($K == $node[0]) {
                $SUBTREE[$K][$node[1]] = array();
                $R = 1;
            }
        }
    }
}

function subtree1(&$Item, &$SUBTREE, $node)
{ //search in tree node2 and insert node1 from tree
    if (count($SUBTREE)) {
        foreach ($SUBTREE as $K => $N) {

            if ($K == $node[1]) {
                $Item = $N;
            }

            subtree1($Item, $SUBTREE[$K], $node);
        }
    }
}

function countSub(&$SUBTREE, $cnt, &$REZ)
{ //calculate max inheriting
    if (count($SUBTREE)) {
        $cnt++;
        foreach ($SUBTREE as $N) {
            countSub($N, $cnt, $REZ);
        }
    } else {
        $REZ[] = $cnt;
    }
}

$ARR_STR = explode('|', $STR);

$TREE = array();

foreach ($ARR_STR as $LINE) {
    $node = explode(' ', $LINE);
    $R = false;
    subtree0($TREE, $node, $R);

    if (!$R) {
        $TREE[$node[0]][$node[1]] = array();
    }
    subtree1($TREE[$node[0]][$node[1]], $TREE, $node);
}
var_dump($TREE);
//$TREE;

$ARR_REZ = array();
foreach ($TREE as $ELEM) {
    countSub($ELEM, 1, $ARR_REZ);
}
var_dump($ARR_REZ);

echo '-----------' . max($ARR_REZ) . '-------------';
