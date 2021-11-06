<?php

function censor($str) {
    $censored = '';

    // finite state automata states
    // 0 : print normal
    // 1 : print asterisk
    $state = 0;

    $cnext = chr(0);
    for ($i = 0; $i < strlen($str); $i++) {
        if ($i < strlen($str)) {
            $c = $str[$i];
        }
        if ($i < strlen($str) - 1) {
            $cnext = $str[$i + 1];
        } else {
            $cnext = chr(0);
        }

        if ($state == 0) {
            if ($c === ' ') {
                $censored .= ' ';
            } else {
                $censored .= $c;
                $state = 1;
            }
        } else if ($state == 1) {
            if ($c === ' ') {
                $censored .= ' ';
                $state = 0;
            } else {
                if (($cnext === ' ') || ($cnext === chr(0))) {
                    $censored .= $c;
                } else {
                    $censored .= '*';
                }
            }
        }
    }

    return $censored;
}

