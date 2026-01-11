<?php

function modexp($base, $exp, $mod): mixed {
    return gmp_intval(gmp_powm($base, $exp, $mod));
}

function modinv($a, $mod): mixed {
    return gmp_intval(gmp_invert($a, $mod));
}

function myPow($k,$e,$N): mixed{
    return gmp_powm(gmp_init($k, 10), gmp_init($e, 10), gmp_init($N, 10));
}

function hashN2Array(array $n2List): array {
    $n2PrintList= [];
    foreach($n2List as $n2) {
        $n2PrintList[] = hash('sha256', $n2, true);
    }

    return $n2PrintList;
}