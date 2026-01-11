<?php

namespace App\Controllers;

use App\Models\Electeur;
use App\Models\Administrateur;
use App\Models\Anonymiseur;
use App\Models\Bulletin;
use App\Models\Commissaire;
use App\Models\Decompteur;

class VoteController{

    protected array $n1;
    protected array $n2;
    protected Administrateur $admin;
    protected Anonymiseur $anony;
    protected Commissaire $commi;
    protected Bulletin $bulletin;

    public function __construct(){
        $n1Json = file_get_contents("n1.json");
        $n2Json = file_get_contents("n2.json");

        $this->n1 = json_decode($n1Json,true);
        $this->n2 = json_decode($n2Json,true);
        $n2Print = hashN2Array($this->n2);

        $this->admin = new Administrateur($this->n1);
        $this->anony = new Anonymiseur($this->n1);
        $this->commi = new Commissaire($this->n1,$n2Print);
    }

    public function index(){
        require("Views/vote.php");
    }

    public function addVote(){
        
        if(!isset($_POST["codeN1"]) || !isset($_POST["codeN2"]) || !isset($_POST["voteOption"]))
        {
            $_SESSION['error_message'] = "Formulaire incomplet.";
            header("Location: index.php");
            exit;
        }

        if(isset($_SESSION["vote_closed"])){
            $_SESSION['error_message'] = "Le vote est clos, veillez réinitialiser le vote avant d'en soumettre un nouveau";
            header("Location: index.php");
            exit;
        }

        $electeur = new Electeur($_POST["codeN1"],$_POST["codeN2"],$_POST["voteOption"]);

        $this->admin->setN1($electeur->getN1());

        // On vérifie N1, s'il n'est pas bon on redirige
        if(!$this->commi->validateN1($this->admin->getN1())){
            $_SESSION['error_message'] = "Les données que vous avez renseignées ne sont pas bonnes !";
            header("Location: index.php");
            exit;
        }

        $this->bulletin = $electeur->createBulletin();
        $blindVote = $this->bulletin->getBlindVote();
        $signedVote = $this->admin->blindSign($blindVote);

        $this->bulletin->setSignature($signedVote);
        $cipherBulletin = $this->bulletin->cipherBulletin();

        $result = $this->anony->handleNewBulletin($electeur->getN1(),$cipherBulletin);

        // Si le N1 fournit n'est plus dans la liste
        // ou n'a pas pu être supprimé on redirige
        if($result[0]== false){
            $_SESSION['error_message'] = "Les données que vous avez renseignées ne sont pas bonnes !";
            header("Location: index.php");
            exit;
        }
        
        $_SESSION['success_message'] = "Vote pris en compte !";
        
        require("Views/vote.php");
    }

    public function reset()
    {
        file_put_contents('./bulletins.json', json_encode([], JSON_PRETTY_PRINT));

        $nbCodes = getenv("NUMBER_OF_ELECTORS"); 

        $n1 = [];
        $n2 = [];

        for ($i = 0; $i < $nbCodes; $i++) {
            $n1[(string)$i] = $this->generateCode();
            $n2[(string)$i] = $this->generateCode();
        }

        file_put_contents('./n1.json', json_encode($n1, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT));
        file_put_contents('./n2.json', json_encode($n2, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT));
        

        unset($_SESSION['vote_closed']);
        $_SESSION['success_message'] = "Le vote a été réinitialisé. Vous pouvez voter à nouveau.";
        header('Location: index.php?p=vote/index');
        exit;
    }

    public function finaliser()
    {

        $_SESSION['vote_closed'] = true;
        $_SESSION['success_message'] = "Le vote est clôturé. Vous pouvez consulter les résultats.";
        
        $content = file_get_contents('./bulletins.json');
        $bulletins = json_decode($content, true) ?? [];

        // On vide n1 pour éviter d'avoir des votes après la fin
        file_put_contents('./n1.json', json_encode([], JSON_PRETTY_PRINT));

        $decompteur = new Decompteur($this->admin,$this->commi);
        $resultats = $decompteur->compter($bulletins);

        $nbElecteurs = (int) getenv('NUMBER_OF_ELECTORS');

        $invalid = $resultats['invalid'] ?? 0;
        unset($resultats['invalid']);

        $nbVotesExprimes = array_sum($resultats);

        $nbAbstention = max(0, $nbElecteurs - $nbVotesExprimes);

        $pctVotants    = $nbElecteurs > 0 ? ($nbVotesExprimes * 100 / $nbElecteurs) : 0;
        $pctAbstention = $nbElecteurs > 0 ? ($nbAbstention    * 100 / $nbElecteurs) : 0;

        $detailsVotes = [];
        foreach ($resultats as $option => $nb) {
            $pct = $nbVotesExprimes > 0 ? ($nb * 100 / $nbVotesExprimes) : 0;
            $detailsVotes[] = [
                'option'    => $option,
                'nombre'    => $nb,
                'pourcent'  => $pct,
            ];
        }

        require './Views/resultats.php';
    }

    protected function generateCode(): string
    {
        $items = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code  = $items[random_int(0, 35)];

        for($i =0; $i<getenv("CODE_LENGTH")-1; $i++)
        {
            $code  .= $items[random_int(0, 35)];
        }

        return $code;
    }
}