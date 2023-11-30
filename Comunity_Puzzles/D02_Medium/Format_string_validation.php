<?
    $text = stream_get_line(STDIN, 1000 + 1, "\n");
    $format = stream_get_line(STDIN, 100 + 1, "\n");

    $replace = [
        '\\' => '\\\\',
        '*'  => '\*',
        '.'  => '\.',
        '^'  => '\^',
        '|'  => '\|',
        '/'  => '\/',
        '!'  => '\!',
        ')'  => '\)',
        '('  => '\(',
        '{'  => '\{',
        '}'  => '\}',
        '['  => '\[',
        ']'  => '\]',
        '$'  => '\$',
        '+'  => '\+',
        '@'  => '\@',
        '&'  => '\&',

        '?' => '.',
        '~' => '.*'
    ];

    /* $format = preg_replace("#([\[\]*+\\^|(){}$])#","\\\\$1", $format);
     $format = str_replace(['?', '~'], ['.','.*'], $format);

     //echo("#" . $format . "#");

     if (preg_match("#" . $format . "#", $text)) {
         echo "MATCH\n";
     } else {
         echo "FAIL\n";
     }*/
