<?php

namespace App\Models;

Class Administrateur{

    protected array $N1List;
    protected string $N1;

    protected string $d;
    protected string $N;
    protected string $e;
    protected string $k;

    public function __construct(array $N1){
        $this->N1List = $N1;
        $this->d = getenv("RSA_D_ADMIN");
        $this->N = getenv("RSA_N_ADMIN");
        $this->e = getenv("RSA_E_ADMIN");
        $this->k = getenv("RSA_K");
    }   

    public function setN1(string $n1){
        $this->N1 = $n1;
    }

    public function getN1(){
        return $this->N1;
    }

    public function blindSign(string $blindVote){

        $m_blind = gmp_init($blindVote, 10);
        $s_blind = gmp_powm($m_blind, gmp_init($this->d, 10), gmp_init($this->N, 10));

        return gmp_strval($s_blind);
    }

    public function checkSignature(string $hash,string $signature):bool
    {
        $e = gmp_init($this->e, 10);
        $N = gmp_init($this->N, 10);
        $k = gmp_init($this->k, 10);

        $vote = gmp_init($hash, 16);
        $ke_mod_N = gmp_powm($k, $e, $N);

        $m_blind = gmp_mod(gmp_mul($vote, $ke_mod_N), $N);
        $blindVote = gmp_strval($m_blind);

        $blindSign = $this->blindSign($blindVote);
        $s_blind_gmp = gmp_init($blindSign, 10);

        $k_inv   = gmp_invert($k, $N);
        $k_inv_e = gmp_powm($k_inv, $e, $N);
        $s = gmp_mod(gmp_mul($s_blind_gmp, $k_inv_e), $N);

        return gmp_strval($s) == $signature;

    }
}