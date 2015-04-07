<?php
    // Read inputs from STDIN. Print outputs to STDOUT.
    fscanf(STDIN, "%d", $n);
    //$t = array(array(8,5),array(3,4),array(2,4),array(2,3),array(1,2));

    for ($i = 0; $i < $n; $i++) {
        fscanf(STDIN, "%s %s",
            $node1,
            $node2
        );
        //$node1 = $t[$i][0];
        //$node2 = $t[$i][1];

        if (!isset($TREE[$node1])) {
            $TREE[$node1] = array(
                'nodes' => array($node2),
                'total' => 0,
                'flag' => 0
            );
        } else {
            $TREE[$node1]['nodes'][] = $node2;
        }
        
        if (!isset($TREE[$node2])) {
            $TREE[$node2] = array(
                'nodes' => array(),
                'total' => 0,
                'flag' => 0
            );
        }
    }

    error_log(var_export($TREE, true));
    error_log(var_export('---------', true));
    echo gextri($TREE)+1;
    echo "\n";


    # ====================================================================
    function gextri($TREE)
    {   
        while (!checkedAllNodes($TREE)) {
            $i = startnode($TREE);
            recursewalk($TREE, $i);
        }
        
        return searchMax($TREE);
    }

    function checkedAllNodes(&$TREE)
    {
        foreach ($TREE as $N) {
            if (!$N['flag']) {
                return 0;
            }
        }
        
        return 1;
    }

    function startnode($TREE) # search start node
    {
        foreach ($TREE as $k => $N) {
            if (!$N['flag']) {
                break;
            }
        }
        
        $flag = 1;
        while ($flag) {
            $flag = 0;
            
            foreach ($TREE as $i => $T) {
                if (in_array($k, $T['nodes'])) {
                    $flag = 1;
                    $k = $i;
                }
            }
        }
        return $k;
    }

    function recursewalk(&$TREE, $i)
    {
        $TREE[$i]['flag'] = 1;
        foreach ($TREE[$i]['nodes'] as $k=>$r) {
            $TREE[$r]['total'] = calcCost($TREE, $i, $k);
            
            recursewalk($TREE, $r);
        }
    }

    function calcCost($TREE, $i, $N) # calculate cost for node N
    {
        $r   = $TREE[$i]['nodes'][$N];
        $sum = $TREE[$i]['total'] + 1;
            
        if ($TREE[$r]['total'] < $sum) {
            return $sum;
        }
        return $TREE[$r]['total'];
    }

    function searchMax($ROOMS)
    {
        $REZ = array();

        foreach ($ROOMS as $R) {
            $REZ[] = $R['total'];
        }
        
        return max($REZ);
    }