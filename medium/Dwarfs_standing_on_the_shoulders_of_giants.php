<?php

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

// Read inputs from STDIN. Print outputs to STDOUT.
for ($i = 0; $i < $n; $i++) {
    //echo "Hello World!\n";
    fscanf(STDIN, "%s %s",
        $node1,
        $node2
    );

    error_log(var_export($node1 . ' ' . $node2, true));
    $node = array($node1, $node2);

    $R = false;
    subtree0($TREE, $node, $R);

    if (!$R) {
        $TREE[$node[0]][$node[1]] = array();
    }

    subtree1($TREE[$node[0]][$node[1]], $TREE, $node);
}

$ARR_REZ = array();

foreach ($TREE as $NODE) {
    countSub($NODE, 1, $ARR_REZ);
}
echo max($ARR_REZ) . "\n";
