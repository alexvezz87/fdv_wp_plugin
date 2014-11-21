<?php

    //AUTORE: Alex Vezzelli - alexsoluzioniweb.it

    class ListaOrdine{
        //definisco gli attributi
        private $apertura;
        private $chiusura;
        private $aperto;
        private $db;
        
        //definisco il costruttore
        public function __construct($wpdb){
            $this->db = $wpdb;
        }
        
        //definisco i metodi
        public function getApertura(){
            return $this->apertura;
        }
        public function getChiusura(){
            return $this->chiusura;
        }
        public function getOrdineAperto(){
            return $this->aperto;
        }
        public function getDB(){
            return $this->db;
        }
        
        public function setApertura($apertura){
            $apertura = addslashes($apertura);
            $this->apertura = $apertura;
        }
        public function setChiusura($chiusura){
            $chiusura = addslashes($chiusura);
            $this->chiusura = $chiusura;
        }
        public function setAperto($aperto){
            $aperto = addslashes($aperto);
            $this->aperto = $aperto;
        }
        
        
        
    }
?>
