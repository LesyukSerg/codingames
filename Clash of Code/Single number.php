<?
    $numbers = [];
    foreach($line as $number) {
        $numbers[$number]++;
    }

    echo array_search(1,$numbers);
    echo "\n";