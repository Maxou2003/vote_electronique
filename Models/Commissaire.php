<?php
namespace App\Models;

Class Commissaire{

    protected array $N1List;
    protected array $hashedN2Array;

    public function __construct(array $N1,array $hashedN2Array){
        $this->N1List = $N1;
        $this->hashedN2Array= $hashedN2Array;
    }   

    public function validateN1(string $n1): bool
    {
        foreach($this->N1List as $n1Element){
            if($n1 == $n1Element){
                return true;
            }
        }
        return false;
    }

    public function validateN2(string $n2): bool
    {
        $n2Hash = hash('sha256', $n2, true);

        return in_array($n2Hash, $this->hashedN2Array);
    }
}