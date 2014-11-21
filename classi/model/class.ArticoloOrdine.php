<?php

//AUTORE: Alex Vezzelli - alexsoluzioniweb.it

class ArticoloOrdine {

    //definisco gli attributi
    private $id_prodotto; //identificativo del prodotto
    private $id_tabella; //indetificativo della tabella del prodotto
    private $id_ordine; //identificativo dell'ordine cliente
    
    private $nome_articolo;
    private $unita_misura;
    private $quantita;
    private $prezzo_unitario;
    private $cassetta_personalizzata;

    //definisco il costruttore
    public function __construct($id_ordine, $id_tabella, $id_prodotto) {
        $this->id_prodotto = $id_prodotto;
        $this->id_tabella = $id_tabella;
        $this->id_ordine = $id_ordine;
        $this->cassetta_personalizzata = 0; //0 di default
    }
    
   
    //definisco i metodi
    public function getID_Prodotto() {
        return $this->id_prodotto;
    }

    public function getID_Tabella() {
        return $this->id_tabella;
    }

    public function getID_Ordine() {
        return $this->id_ordine;
    }
    
    public function getNomeArticolo(){
        return $this->nome_articolo;
    }
    
    public function setNomeArticolo($nome){
        $this->nome_articolo = $nome;
    }
    
    public function getUnitaMisura(){
        return $this->unita_misura;
    }
    
    public function setUnitaMisura($unita){
        $this->unita_misura = $unita;
    }
    
    public function getQuantita() {
        return $this->quantita;
    }

    public function getPrezzoUnitario() {
        return $this->prezzo_unitario;
    }

    public function getCassettaPersonalizzata() {
        return $this->cassetta_personalizzata;
    }

    public function setID_Prodotto($id_prodotto) {
        $this->id_prodotto = $id_prodotto;
    }

    public function setID_Tabella($id_tabella) {
        $this->id_tabella = $id_tabella;
    }

    public function setID_Ordine($id_ordine) {
        $this->id_ordine = $id_ordine;
    }

    public function setQuantita($quantita) {
        $this->quantita = $quantita;
    }

    public function setPrezzoUnitario($pu) {
        $this->prezzo_unitario = $pu;
    }

    public function setCassettaPersonalizzata($value) {
        $this->cassetta_personalizzata = $value;
    }

}

?>
