<?php
    function oneGlass()
    {
        return [
            ' ***  ',
            ' * *  ',
            ' * *  ',
            '***** '
        ];
    }

    function nGlasses($n)
    {
        $line = [];
        $glass = oneGlass();

        for ($i = 0; $i < 4; $i++) {
            $line[$i] = substr(str_repeat($glass[$i], $n), 0, -1);
        }

        return $line;
    }

    function centerPyramid($level)
    {
        foreach ($level as $i => $line) {
            $level[$i] = "   $line   ";
        }

        return $level;
    }


    function buildPyramid($N)
    {
        $pyramid = [];
        $count = $build = 1;

        while ($count <= $N) {
            $level = nGlasses($build);
            $pyramid = array_merge(centerPyramid($pyramid), $level);

            $build++;
            $count += $build;
        }

        return $pyramid;
    }

    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));
    fscanf(STDIN, "%d", $N);
    $pyramid = buildPyramid($N);

    foreach ($pyramid as $line) {
        echo $line . "\n";
    }
