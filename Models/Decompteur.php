<?php

namespace App\Models;

use App\Models\Administrateur;
use App\Models\Commissaire;

Class Decompteur{
    
    protected string $d;
    protected string $N;

    protected Administrateur $admin;
    protected Commissaire $commissaire;

    public function __construct(Administrateur $admin, Commissaire $commissaire){
        $this->d = getenv("RSA_D_DECOMPTEUR");
        $this->N = getenv("RSA_N_DECOMPTEUR");
        $this->commissaire = $commissaire;
        $this->admin = $admin;
    }   

    public function compter(array $bulletins): array
    {
        $results = [];
        $results["invalid"] = 0;
        $results["history"] = [];

        $d = gmp_init($this->d, 10);
        $N = gmp_init($this->N, 10);

        foreach ($bulletins as $cipherBulletin) {

            $c = gmp_init($cipherBulletin, 10);
            $m = gmp_powm($c, $d, $N);

            // entier -> binaire
            $binary = gmp_export($m);

            if ($binary === '') {
                $results["invalid"]++;
                continue;
            }

            $data = json_decode($binary, true);

            if (!is_array($data)) {
                $results["invalid"]++;
                continue;
            }

            $hash      = $data['hash'] ?? null;
            $signature = $data['signature'] ?? null;
            $vote      = $data['vote'] ?? null;
            $n2        = $data['n2'] ?? null;

            if (!$this->admin->checkSignature($hash, $signature)) {
                $results["invalid"]++;
                continue;
            }

            if (!$this->commissaire->validateN2($n2)) {
                $results["invalid"]++;
                continue;
            }

            if (!isset($results[$vote])) {
                $results[$vote] = 0;
                $results["history"][] = [$vote,$n2];
            }
            $results[$vote]++;
        }

        return $results;
    }

}