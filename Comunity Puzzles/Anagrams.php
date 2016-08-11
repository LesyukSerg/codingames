<?php
    $phrase = trim(fgets(STDIN));

    $wordLettersCount = [];
    $words = explode(' ', $phrase);
    foreach ($words as $word) {
        $wordLettersCount[] = strlen($word);
    }

    $phrase = step_two($phrase);

    $phrase = step_three($phrase);

    $phrase = step_one($phrase);

    $phrase = step_four($phrase, $wordLettersCount);

    echo $phrase . "\n";

    #=================================================================================================================
    #=================================================================================================================
    #=================================================================================================================

    function everyNLetter($N, $phrase)
    {
        $pos = $inPhrase = $every = [];
        $ABC = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $countP = strlen($phrase);

        for ($i = $N - 1; $i < 26; $i += $N) {
            $every[] = $ABC[$i];
        }

        for ($i = 0; $i < $countP; $i++) {
            if (in_array($phrase[$i], $every)) {
                $inPhrase[] = $phrase[$i];
                $pos[] = $i;
            }
        }

        return array('in' => $inPhrase, 'pos' => $pos);
    }

    function step_one($phrase)
    {
        //1) Find every 2nd letter of the alphabet in the phrase (B, D, F, etc.)
        //and reverse their order within the phrase.
        $every = everyNLetter(2, $phrase);
        $every['in'] = array_reverse($every['in']);

        foreach ($every['pos'] as $p) {
            $phrase[$p] = array_shift($every['in']);
        }

        return $phrase;
    }

    function step_two($phrase)
    {
        //2) Find every 3rd letter of the alphabet in the phrase (C, F, I, etc.)
        //and shift their positions one to the right, with the last letter wrapped around to the first position.
        $every = everyNLetter(4, $phrase);

        $last = array_pop($every['in']);
        array_unshift($every['in'], $last);

        foreach ($every['pos'] as $p) {
            $phrase[$p] = array_shift($every['in']);
        }

        return $phrase;
    }

    function step_three($phrase)
    {
        //3) Find every 4th letter of the alphabet in the phrase (D, H, L, etc.)
        //and shift their positions one to the left, with the first letter wrapped around to the last position.
        $every = everyNLetter(3, $phrase);

        $every['in'][] = array_shift($every['in']);

        foreach ($every['pos'] as $p) {
            $phrase[$p] = array_shift($every['in']);
        }

        return $phrase;
    }

    function step_four($phrase, $wordLettersCount)
    {
        //4) Count the number of letters in each word,
        //and reverse that list of numbers, re-applying the revised word lengths to the letter sequence.
        $wordLettersCount = array_reverse($wordLettersCount);
        $phrase = str_replace(' ', '', $phrase);

        $pos = 0;
        foreach ($wordLettersCount as $symbols) {
            $pos += $symbols;

            $start = substr($phrase, 0, $pos);
            $end = substr($phrase, $pos, strlen($phrase));

            $phrase = $start . ' ' . $end;
            $pos++;
        }

        return trim($phrase);
    }

