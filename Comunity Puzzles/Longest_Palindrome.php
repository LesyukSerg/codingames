<?
    function findLongest($s, $left, $right, $len)
    {
        while ($left >= 0 && $right < $len && $s[$left] == $s[$right]) {
            $left--;
            $right++;
        }

        return substr($s, ++$left, $right - $left);
    }

    function fillIt(&$array, $item, &$maxSize)
    {
        $pLen = strlen($item);

        if ($pLen > $maxSize) {
            $maxSize = $pLen;
            $array = [$item];
        } elseif ($pLen == $maxSize) {
            $array[] = $item;
        }
    }

    fscanf(STDIN, "%s", $s);
    //error_log(var_export($s, true));
    $len = strlen($s);
    $maxSize = 0;
    $palindromes = [];

    for ($i = 1; $i < $len - 1; $i++) {
        if ($s[$i - 1] == $s[$i]) {
            $palindrome = findLongest($s, $i - 1, $i, $len);
            fillIt($palindromes, $palindrome, $maxSize);

        } elseif ($s[$i - 1] == $s[$i + 1]) {
            $palindrome = findLongest($s, $i - 1, $i + 1, $len);
            fillIt($palindromes, $palindrome, $maxSize);
        }
    }

    //error_log(var_export($palindromes, true));
    echo implode("\n", $palindromes);

    //error_log(var_export($polindroms, true));
