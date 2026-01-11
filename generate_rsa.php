<?php

// Génère une clé RSA jouet : e, d, N sous forme de strings décimales
function generateRsaKeypair(int $bitsPerPrime = 166): array
{
    // 1. Générer deux grands nombres premiers p et q
    $p = generatePrime($bitsPerPrime);
    $q = generatePrime($bitsPerPrime);

    // s'assurer que p != q
    while (gmp_cmp($p, $q) === 0) {
        $q = generatePrime($bitsPerPrime);
    }

    // 2. n = p * q
    $n = gmp_mul($p, $q);

    // 3. phi(n) = (p - 1) * (q - 1)
    $p1 = gmp_sub($p, 1);
    $q1 = gmp_sub($q, 1);
    $phi = gmp_mul($p1, $q1);

    // 4. Choisir e premier avec phi(n)
    // 65537 est un choix classique si gcd(e, phi) == 1
    $e = gmp_init('65537', 10);
    while (gmp_cmp(gmp_gcd($e, $phi), 1) !== 0) {
        $e = gmp_nextprime($e); // ou incrémenter
    }

    // 5. d = e^{-1} mod phi(n)
    $d = gmp_invert($e, $phi);
    if ($d === false) {
        throw new RuntimeException('Impossible de calculer l’inverse modulaire.');
    }

    return [
        'e' => gmp_strval($e), // exposant public (décimal)
        'd' => gmp_strval($d), // exposant privé (décimal)
        'n' => gmp_strval($n), // modulus (décimal, ~100 chiffres)
    ];
}

// Génère un nombre premier de ~ $bits bits en utilisant GMP
function generatePrime(int $bits): \GMP
{
    $bytes = intdiv($bits + 7, 8); // nb d’octets

    while (true) {
        $bin = random_bytes($bytes);
        // Forcer le bit de poids fort pour avoir la bonne taille
        $bin[0] = $bin[0] | chr(0x80);
        $candidate = gmp_import($bin);

        // S’assurer que c’est impair (sinon +1)
        if (gmp_strval(gmp_mod($candidate, 2)) === '0') {
            $candidate = gmp_add($candidate, 1);
        }

        // Trouver le prochain nombre premier
        $prime = gmp_nextprime($candidate);

        // Vérifier que le nombre de bits est suffisant
        if (gmp_strval(gmp_scan1($prime, $bits - 1)) !== '') {
            return $prime;
        }
    }
}

$key = generateRsaKeypair(64); 

echo "e = " . $key['e'] . PHP_EOL;
echo "d = " . $key['d'] . PHP_EOL;
echo "n = " . $key['n'] . PHP_EOL;
