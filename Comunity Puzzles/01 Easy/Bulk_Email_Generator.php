<?php
    fscanf(STDIN, "%d", $N);
    $choices = $lines = [];

    for ($i = 0; $i < $N; $i++) {
        $k = $i;
        $line = getLine($i);
        $choices[$k] = getChoices($line);
        $lines[] = $line;
    }

    //error_log(var_export($lines, true));
    //error_log(var_export($choices, true));
    generateResult($lines, $choices);

# ====================================================================================================================

    function generateResult($lines, $choices)
    {
        $n = 0;

        foreach ($lines as $k => $line) {
            if (count($choices[$k])) {
                foreach ($choices[$k] as $choice) {
                    $line = chooseChoice($line, $choice, $n);
                    $n++;
                }
            }

            echo $line . "\n";
        }
    }

    function getLine(&$i)
    {
        $line = stream_get_line(STDIN, 10000 + 1, "\n");

        if (strpos($line,'(') !== false) {
            while (strpos($line,')') === false) {
                $i++;
                $line .= "\n". stream_get_line(STDIN, 10000 + 1, "\n");
            }
        }

        $line = preg_replace('#\n#m', '\\n', $line);

        return $line;
    }

    function getChoices($line): array
    {
        preg_match_all("#\((.+?)\)#s", $line, $found);

        return $found[0];
    }

    function chooseChoice($line, $choice, $n)
    {
        $choiceArray = explode('|', trim(trim($choice, '('), ')'));

        if (!isset($choiceArray[$n])) {
            $n = newN($n, count($choiceArray));
        }


        $line = str_replace($choice, $choiceArray[$n], $line);
        $line = str_replace("\\n", "\n", $line);

        return $line;
    }

    function newN($n, $count) {
        $n++;
        $n = ($n++ % $count);

        return !$n ? --$count : --$n;
    }