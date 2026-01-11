<?php

namespace App\Models;

class Bulletin {

    protected string $hash;
    protected string $vote;
    protected string $salt;
    protected string $n2;

    protected int $saltLength = 16;
    protected string $signature;

    protected string $eAdmin;
    protected string $nAdmin;
    protected string $k;

    protected string $eDecompteur;
    protected string $nDecompteur;

    public function __construct(string $vote, string $N2) {
        $this->salt = random_bytes($this->saltLength);

        $this->eAdmin = getenv('RSA_E_ADMIN');
        $this->nAdmin = getenv('RSA_N_ADMIN');
        $this->k      = getenv('RSA_K');

        $this->eDecompteur = getenv('RSA_E_DECOMPTEUR');
        $this->nDecompteur = getenv('RSA_N_DECOMPTEUR');

        $data = $vote . '|' . $N2 . '|' . $this->salt;
        $this->vote = $vote;
        $this->n2 = $N2;
        $this->hash = hash('sha256', $data, true);
    }

    public function getHash(): string {
        return bin2hex($this->hash);
    }

    public function getBlindVote(): string {

        // On passe par GMP car les valeurs sont très grandes
        // GMP permet d'éviter de caster des valeurs et de perdre en précision.
        // Il permet  également d'optimiser la vitesse d'exécution des opérations

        $e = gmp_init($this->eAdmin, 10);
        $N = gmp_init($this->nAdmin, 10);
        $k = gmp_init($this->k, 10);

        $hashNum = gmp_init($this->getHash(), 16);
        $m = gmp_mod($hashNum, $N);

        $ke_mod_N = gmp_powm($k, $e, $N);

        $m_blind = gmp_mod(gmp_mul($m, $ke_mod_N), $N);

        return gmp_strval($m_blind);
    }

    public function setSignature(string $s_blind): void {

        $e = gmp_init($this->eAdmin, 10);
        $N = gmp_init($this->nAdmin, 10);
        $k = gmp_init($this->k, 10);

        $s_blind_gmp = gmp_init($s_blind, 10);

        $k_inv   = gmp_invert($k, $N);
        $k_inv_e = gmp_powm($k_inv, $e, $N);

        $s = gmp_mod(gmp_mul($s_blind_gmp, $k_inv_e), $N);

        $this->signature = gmp_strval($s);
    }

    public function cipherBulletin() : string
    {
        if(!isset($this->signature)){
            return "";
        }

        $message = json_encode([
            'vote'      => $this->vote,   
            'n2' => $this->n2,
            'hash' => $this->getHash(),
            'signature' => $this->signature          
        ]);

        $m_enc = gmp_import($message);

        $e_gmp  = gmp_init($this->eDecompteur, 10);
        $N_gmp  = gmp_init($this->nDecompteur, 10);

        $m_bits = strlen(gmp_strval($m_enc, 2));
        $N_bits = strlen(gmp_strval($N_gmp, 2));

        $c = gmp_powm($m_enc, $e_gmp, $N_gmp);
        return gmp_strval($c);   
    }
}
