<?php
    fscanf(STDIN, "%d", $N);
    fscanf(STDIN, "%d", $M);

    $allow = $person = [];
    $no = $yes = 0;

    for ($i = 0; $i < $N; $i++) {
        fscanf(STDIN, "%s %d", $personName, $nbVote);
        $allow[$personName] = $nbVote;
        $person[$personName]['yes'] = 0;
        $person[$personName]['no'] = 0;
    }

    for ($i = 0; $i < $M; $i++) {
        fscanf(STDIN, "%s %s", $voterName, $voteValue);

        if (isset($allow[$voterName]) && isset($person[$voterName])) {
            if ($allow[$voterName]) {
                $allow[$voterName]--;

                if ($voteValue == 'Yes') {
                    $person[$voterName]['yes']++;
                    $yes++;
                } elseif ($voteValue == 'No') {
                    $person[$voterName]['no']++;
                    $no++;
                }
            } else {
                $yes -= $person[$voterName]['yes'];
                $no -= $person[$voterName]['no'];
                unset($person[$voterName]);
            }
        }
    }

    echo "$yes $no\n";
    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));
