<?
    //define(STDIN, fopen('input.txt','r'));
    fscanf(STDIN, "%d", $n);  // $n the number of adjacency relations

    for ($i = 0; $i < $n; $i++) {
        fscanf(STDIN, "%d %d",
            $node1, // the ID of a person which is adjacent to yi
            $node2 // the ID of a person which is adjacent to xi
        );

        if (!isset($TREE[$node1])) {
            $TREE[$node1] = array(
                'parent' => 'N',
                'nodes'  => array($node2),
                'total'  => 0,
                'flag'   => 0
            );
        } else {
            $TREE[$node1]['nodes'][] = $node2;
        }

        if (!isset($TREE[$node2])) {
            $TREE[$node2] = array(
                'parent' => array($node1),
                'nodes'  => array(),
                'total'  => 0,
                'flag'   => 0
            );
        } else {
            if (!is_array($TREE[$node2]['parent'])) {
                $TREE[$node2]['parent'] = array($node1);

            } elseif (!in_array($node1, $TREE[$node2]['parent'])) {
                $TREE[$node2]['parent'][] = $node1;
            }
        }
    }

    $cost = array();
    error_log(var_export('el = ' . count($TREE), true));
    //error_log(var_export($TREE, true));
    // die();
    $NEED = [];


    $one = gextri_mod($TREE, key($TREE));
    $two = gextri_mod($TREE, $one['node']);

    echo ceil($two['cost'] / 2) . "\n";

    //echo("1\n"); // The minimal amount of steps required to completely propagate the advertisement
    # =============================================================================================
    # =============================================================================================
    # =============================================================================================

    function gextri_mod($TREE, $START)
    {
        recursewalk($TREE, $START);

        return searchMax($TREE);
    }

    function recursewalk(&$TREE, $i)
    {
        //error_log(var_export($i, true));
        //error_log(var_export($TREE, true));
        //error_log(var_export('---------', true));
        if (!$TREE[$i]['flag']) {
            $TREE[$i]['flag'] = 1;

            if ($TREE[$i]['parent'] !== 'N') { //UP
                //error_log(var_export('UP', true));
                foreach ($TREE[$i]['parent'] as $r) {  //DOWN
                    //error_log(var_export('DOWN', true));
                    if (!$TREE[$r]['flag']) {
                        $TREE[$r]['total'] = calcCost($TREE[$i]['total'], $TREE[$r]['total']);

                        recursewalk($TREE, $r);
                    }
                }

                if (!$TREE[$r]['flag']) {
                    $TREE[$r]['total'] = calcCost($TREE[$i]['total'], $TREE[$r]['total']);

                    recursewalk($TREE, $r);
                }
            }

            foreach ($TREE[$i]['nodes'] as $k => $r) {  //DOWN
                //error_log(var_export('DOWN', true));
                if (!$TREE[$r]['flag']) {
                    $TREE[$r]['total'] = calcCost($TREE[$i]['total'], $TREE[$r]['total']);

                    recursewalk($TREE, $r);
                }
            }
        }
    }

    function calcCost($total_i, $total_r) # calculate cost for node N
    {
        $sum = $total_i + 1;

        if ($total_r < $sum) {
            return $sum;
        }

        return $total_r;
    }

    function searchMax(&$TREE)
    {
        $REZ = array();

        foreach ($TREE as $k => $t) {
            $REZ[$k] = $t['total'];
        }

        $max = max($REZ);
        $node = array_search($max, $REZ);

        return array('node' => $node, 'cost' => $max);
    }
