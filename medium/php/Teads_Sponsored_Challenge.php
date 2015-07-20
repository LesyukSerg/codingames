<?
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
                'parent' => $node1,
                'nodes'  => [],
                'total'  => 0,
                'flag'   => 0
            );
        } else {
            $TREE[$node2]['parent'] = $node1;
        }
    }

    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));
    $cost = [];
    foreach ($TREE as $N => $V) {
        $cost[] = gextri_mod($TREE, $N);
    }
    echo min($cost) . "\n";

    //echo("1\n"); // The minimal amount of steps required to completely propagate the advertisement
    # =============================================================================================
    # =============================================================================================
    # =============================================================================================

    function gextri_mod($TREE, $START)
    {
        recursewalk($TREE, $START);

        //error_log(var_export($TREE, true)); die;
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
                $r = $TREE[$i]['parent'];

                if (!$TREE[$r]['flag']) {
                    $TREE[$r]['total'] = calcCost($TREE, $i, $r);

                    recursewalk($TREE, $r);
                }
            }

            foreach ($TREE[$i]['nodes'] as $k => $r) {  //DOWN
                //error_log(var_export('DOWN', true));
                if (!$TREE[$r]['flag']) {
                    $TREE[$r]['total'] = calcCost($TREE, $i, $r);

                    recursewalk($TREE, $r);
                }
            }
        }
    }

    function calcCost($TREE, $i, $r) # calculate cost for node N
    {
        $sum = $TREE[$i]['total'] + 1;

        if ($TREE[$r]['total'] < $sum) {
            return $sum;
        }


        return $TREE[$r]['total'];
    }

    function searchMax($TREE)
    {
        $REZ = [];

        foreach ($TREE as $t) {
            $REZ[] = $t['total'];
        }

        return max($REZ);
    }
