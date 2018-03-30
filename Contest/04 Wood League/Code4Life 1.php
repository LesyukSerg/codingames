<?php
    function gotoModule($module)
    {
        echo "GOTO " . $module . "\n";
    }

    function connect($module, $data)
    {
        echo "CONNECT " . $data . "\n";
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

    $times = $id = 0;
    $status = 0; // 0 -empty 1 - carry

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

        $healths = [];
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

            if ($one['carriedBy'] == -1) {
                $healths[$health] = $one;

            } elseif ($one['carriedBy'] == 0) {
                if ($one['health'] == -1) {
                    $need_diagnosis[$sampleId] = $one;
                } else {
                    $mySample = $one;
                }
            }
        }


        $pos = $my['target'];

        if ($pos == 'START_POS') {
            gotoModule('SAMPLES');

        } elseif ($pos == 'SAMPLES') {
            if (!$status) {
                connect('SAMPLES', 1);
                $status++;
            } else {
                $status = 0;;
                gotoModule('DIAGNOSIS');
            }
        } elseif ($pos == 'DIAGNOSIS') {
            if (count($need_diagnosis)) {
                $one = array_shift($need_diagnosis);
                connect('SAMPLES', $one['sampleId']);
            } else {
                gotoModule('MOLECULES');
            }
        } elseif ($pos == 'MOLECULES') {
            $molecule = needMolecules($my['storage'], $mySample['cost']);

            if ($molecule) {
                connect('MOLECULES', $molecule);
            } else {
                gotoModule('LABORATORY');
            }
        } elseif ($pos == 'LABORATORY') {
            if (!$status) {
                $status++;
                connect('MOLECULES', $mySample['sampleId']);
            } else {
                $status = 0;
                gotoModule('SAMPLES');
            }
        }

        /*//do {
        error_log(var_export($id, true));

        if (!count($healths) && $times < 4) {
            $times++;
            gotoAndConnect('SAMPLES', 1, $my['target']);

        } else {
            $times = 0;
            error_log(var_export($samples, true));
            die;



            //error_log(var_export($id, true));
            //error_log(var_export($samples, true));
            $sample = $samples[$id];

            if ($samples[$id]['carriedBy'] != 0) {
                gotoAndConnect('DIAGNOSIS', $id, $my['target']);
            } else {
                $molecule = needMolecules($my['storage'], $sample['cost']);

                if ($molecule) {
                    gotoAndConnect('MOLECULES', $molecule, $my['target']);
                } else {
                    gotoAndConnect('LABORATORY', $id, $my['target']);
                    error_log(var_export($my['target'], true));
                    if ($my['target'] == 'LABORATORY') {
                        $id = 0;
                    }
                }
            }
        }*/
    }
