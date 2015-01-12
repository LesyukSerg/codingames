<?php
// Read inputs from STDIN. Print outputs to STDOUT.
fscanf(STDIN, "%d", $n);

for ($i = 0; $i < $n; $i++) {
    //echo "Hello World!\n";
    fscanf(STDIN, "%s %s",
        $node1,
        $node2
    );

    if(!isset($TREE[$node1])) {
        $TREE[$node1] = array(
            'cost' => 1,
            'nodes' => array($node2),
            'total' => 0
        );
    } else {
        $TREE[$node1]['nodes'][] = $node2;
    }
    
    if(!isset($TREE[$node2])) {
        $TREE[$node2] = array(
            'cost' => 1,
            'nodes' => array(),
            'total' => 0
        );
    }
}

echo gextri($TREE)+1;
echo "\n";


# ====================================================================
function gextri($TREE)
{   
    $i = startkeynode($TREE);
    
    recursewalk($TREE, $i);

    return searchMax($TREE);
}

function startkeynode($TREE) # search start node
{
    $k = key($TREE);
    $flag = 1;
    while($flag) {
        $flag = 0;
        foreach($TREE as $i => $T) {
            if(in_array($k, $T['nodes'])) {
                $flag = 1;
                $k = $i;
            }
        }
    }
    return $k;
}

function recursewalk(&$TREE, $i)
{
    foreach($TREE[$i]['nodes'] as $k=>$r) {
        $TREE[$r]['total'] = calcCost($TREE, $i, $k);
        
        recursewalk($TREE, $r);
    }
}

function calcCost($TREE, $i, $N) # calculate cost for node N
{
    $r = $TREE[$i]['nodes'][$N];
    $sum = $TREE[$i]['total'] + $TREE[$r]['cost'];
        
    if($TREE[$r]['total'] < $sum)
        return $sum;

    return $TREE[$r]['total'];
}

function searchMax($ROOMS)
{
    $REZ = array();

    foreach($ROOMS as $R) {
        $REZ[] = $R['total'];
    }
    
    return max($REZ);
}