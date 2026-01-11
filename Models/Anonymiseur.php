<?php

namespace App\Models;

Class Anonymiseur{

    protected array $N1List;
    protected array $cipherBulletinList;

    public function __construct(array $N1){
        $this->N1List = $N1;
        $this->cipherBulletinList = [];
    }
    
    public function handleNewBulletin(string $N1, string $cipherBulletin){
        if (!in_array($N1, $this->N1List, true)) {
            return [false, null];
        }

        $path = './n1.json';
        $bulletinsPath = './bulletins.json';

        $json = file_get_contents($path);
        $data = json_decode($json, true); 

        $jsonBulletins = file_get_contents($bulletinsPath);
        $bulletins = $jsonBulletins ? json_decode($jsonBulletins, true) : [];

        if (!is_array($data)) {
            return [false, null];
        }

        if (!is_array($bulletins)) {
            $bulletins = [];
        }

        // supprime l’entrée "clé" => "N1"
        $key = array_search($N1, $data, true);
        if ($key !== false) {
            unset($data[$key]); 
        }
        // On met à jour  n1.json 
        file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $this->N1List = $data;
        $this->cipherBulletinList[] = $cipherBulletin;

        $bulletins[] = $cipherBulletin;

        // On ajoute le bulletins à notre json
        file_put_contents(
            $bulletinsPath,
            json_encode($bulletins, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        return [true, $cipherBulletin];
    }
}