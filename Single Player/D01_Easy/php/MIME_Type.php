<?

    fscanf(STDIN, "%d", $N); // Number of elements which make up the association table.
    fscanf(STDIN, "%d", $Q);// Number Q of file names to be analyzed.

    $mimes = [];
    for ($i = 0; $i < $N; $i++) {
        fscanf(STDIN, "%s %s", $ext, $mimeType);
        $mimes[strtolower($ext)] = $mimeType;
    }

    for ($i = 0; $i < $Q; $i++) {
        $fileName = stream_get_line(STDIN, 512, "\n"); // One file name per line.
        $pos = strrpos($fileName, '.');

        if ($pos !== false) {
            $fileExt = strtolower(substr($fileName, ++$pos, strlen($fileName)));

            if (isset($mimes[$fileExt])) {
                echo $mimes[$fileExt] . "\n";
            } else {
                echo "UNKNOWN\n";
            }
        } else {
            // For each of the Q filenames, display on a line the corresponding MIME type. If there is no corresponding type, then display UNKNOWN.
            echo "UNKNOWN\n";
        }
    }

    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));
