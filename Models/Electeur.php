<?php
namespace App\Models;

use App\Models\Bulletin;

class Electeur{

    protected string $N1;
    protected string $N2;
    protected string $vote;
    protected Bulletin $bulletin;

    public function __construct(string $N1, string $N2, string $vote) {
        $this->N1 = $N1;
        $this->N2 = $N2;
        $this->vote = $vote;
    }

    public function getN1(){
        return $this->N1;
    }

    public function createBulletin(){
        $this->bulletin = new Bulletin($this->vote, $this->N2);

        return $this->bulletin;
    }

}