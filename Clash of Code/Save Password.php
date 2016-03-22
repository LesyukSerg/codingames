<?php
    // save password
    // > 8 letters, didgit, a-z, A-Z

    $PassWord = "SFEg350fdg";

    if (preg_match("/[\da-zA-z]{8,}/", $PassWord)) {
        echo "true\n";
    } else {
        echo "false\n";
    }