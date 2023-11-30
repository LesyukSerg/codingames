<?php
    function gotoAndConnect($module, $data, $position)
    {
        if ($position != $module) {
            echo "GOTO " . $module . "\n";
        } else {
            echo "CONNECT " . $data . "\n";
        }
    }

    function needMolecules($storage, $sample_cost)
    {
        $M = 'ABCDE';

        foreach ($sample_cost as $i => $cnt) {
            if ($storage[$i] < $cnt) {
                return $M[$i];
            }
        }

        return 0;
    }

    /**
     * Bring data on patient samples from the diagnosis machine to the laboratory with enough molecules to produce medicine!
     **/

    fscanf(STDIN, "%d", $projectCount);

    for ($i = 0; $i < $projectCount; $i++) {
        fscanf(STDIN, "%d %d %d %d %d", $a, $b, $c, $d, $e);
    }

    $step = 0;
    $carrySamples = 0;

    // game loop
    while (true) {
        $samples = [];
        $one = $enemy = $my = [];
        for ($i = 0; $i < 2; $i++) {
            fscanf(STDIN, "%s %d %d %d %d %d %d %d %d %d %d %d %d",
                $one['target'],
                $one['eta'],
                $one['score'],
                $one['storage'][],//A
                $one['storage'][],//B
                $one['storage'][],//C
                $one['storage'][],//D
                $one['storage'][],//E
                $one['expertise']['A'],
                $one['expertise']['B'],
                $one['expertise']['C'],
                $one['expertise']['D'],
                $one['expertise']['E']
            );

            if ($i) {
                $enemy = $one;
            } else {
                $my = $one;
            }
        }

        fscanf(STDIN, "%d %d %d %d %d",
            $availableA,
            $availableB,
            $availableC,
            $availableD,
            $availableE
        );

        fscanf(STDIN, "%d", $sampleCount);

        for ($i = 0; $i < $sampleCount; $i++) {
            $one = [];
            fscanf(STDIN, "%d %d %d %s %d %d %d %d %d %d",
                $sampleId,
                $one['carriedBy'],
                $one['rank'],
                $one['expertiseGain'],
                $health,
                $costA,
                $costB,
                $costC,
                $costD,
                $costE
            );
            $one['sampleId'] = $sampleId;
            $one['health'] = $health;
            $one['cost'] = [$costA, $costB, $costC, $costD, $costE];

            $samples[$sampleId] = $one;
            $health[$health] = $one;
        }

        //do {


        if (!count($carrySamples)) {
            krsort($health);
            $one = current($health);
            $id = $one['sampleId'];
        }

        //error_log(var_export($id, true));
        //error_log(var_export($samples, true));
        error_log(var_export($samples[$id]['carriedBy'], true));
        //die;

        if ($samples[$id]['carriedBy'] != 0) {
            gotoAndConnect('DIAGNOSIS', $sample['sampleId'], $my['target']);
        } else {
            $molecule = needMolecules($my['storage'], $sample['cost']);

            if ($molecule) {
                gotoAndConnect('MOLECULES', $molecule, $my['target']);
            } else {
                gotoAndConnect('LABORATORY', $sample['sampleId'], $my['target']);
                error_log(var_export($my['target'], true));
                if ($my['target'] == 'LABORATORY') {
                    unset($carrySamples);
                }
            }
        }
    }

