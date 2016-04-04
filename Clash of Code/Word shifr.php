<?php
    /**
     * Auto-generated code below aims at helping you parse
     * the standard input according to the problem statement.
     **/
    $convert = new Converter();
    fscanf(STDIN, "%s", $rom1);
    fscanf(STDIN, "%s", $rom2);

    $N1 = $convert->toNormal($rom1);
    $N2 = $convert->toNormal($rom2);

    echo $convert->toRome($N1 + $N2) . "\n";


    class Converter
    {
        public $values;

        public function __construct()
        {
            $this->values = array(
                "M" => 1000,
                "D" => 500,
                "C" => 100,
                "L" => 50,
                "X" => 10,
                "V" => 5,
                "I" => 1
            );
        }


        function toNormal($R)
        {
            $N = $k = 0;
            $arrNumbers = [];
            $arrNumbers[$k] = $R[0];
            for ($i = 1; $i < strlen($R) - 1; $i++) {
                if ($this->values[$R[$i]] < $this->values[$R[$i + 1]]) {
                    $k++;
                    $arrNumbers[$k] = "";
                }
                $arrNumbers[$k] .= $R[$i];
            }
            $arrNumbers[$k] .= $R[$i];

            foreach ($arrNumbers as $R) {
                $subN = 0;
                for ($i = 0; $i < strlen($R); $i++) {
                    if ($subN >= $this->values[$R[$i]]) {
                        $subN += $this->values[$R[$i]];
                    } else {
                        $subN = $this->values[$R[$i]] - $subN;
                    }
                }

                $N += $subN;
            }

            return $N;
        }

        function toRoman($N)
        {
            foreach ($this->values as $RomanValue => $value) {

            }


            if ($N >= 1000) {
                return "M" . $this->toRoman($N - 1000);

            } elseif ($N >= 500) {
                if ($N >= 900) {
                    return "C" . $this->toRoman($N + 100);
                } else {
                    return "D" . $this->toRoman($N - 500);
                }

            } elseif ($N >= 100) {
                if ($N >= 400) {
                    return "C" . $this->toRoman($N + 100);
                } else {
                    return "C" . $this->toRoman($N - 100);
                }

            } elseif ($N >= 50) {
                if ($N >= 90) {
                    return "X" . $this->toRoman($N + 10);
                } else {
                    return "L" . $this->toRoman($N - 50);
                }

            } elseif ($N >= 10) {
                if ($N >= 40) {
                    return "X" . $this->toRoman($N + 10);
                } else {
                    return "X" . $this->toRoman($N - 10);
                }

            } elseif ($N >= 5) {
                if ($N > 8) {
                    return "I" . $this->toRoman($N + 1);
                } else {
                    return "V" . $this->toRoman($N - 5);
                }

            } else {
                if ($N > 3) {
                    return "I" . $this->toRoman($N + 1);
                } elseif ($N > 0) {
                    return "I" . $this->toRoman($N - 1);
                }
            }

            return "";
        }
    }

