<?php

    //AUTORE: Alex Vezzelli - alexsoluzioniweb.it
    
    class Cassetta{
        //definisco le proprietà
        private $tipologia_cassetta;
        private $num_prodotti;
        private $peso;
        private $cliente;
        private $prezzo;
        private $db;
        private $id_foto;
        private $id;
        
        //definisco il costruttore
        public function __construct($db){
            $this->db = $db;
        }
        
        //definisco i metodi
        public function getDB(){
            return $this->db;
        }
        public function getTipologiaCassetta(){
            return $this->tipologia_cassetta;
        }
        public function getNumProdotti(){
            return $this->num_prodotti;
        }
        public function getPeso(){
            return $this->peso;
        }
        public function getCliente(){
            return $this->cliente;
        }
        public function getPrezzo(){
            return $this->prezzo;
        }
        public function getID_Foto(){
            return $this->id_foto;
        }
        
        public function setID($id){
            $this->id = $id;
        }
        
        public function getID(){
            return $this->id;
        }
       
        
        public function setCassetta($tipologia_cassetta, $num_prodotti, $peso, $cliente, $prezzo, $foto){
            $this->tipologia_cassetta = addslashes($tipologia_cassetta);
            $this->num_prodotti = addslashes($num_prodotti);
            $this->peso = addslashes($peso);
            $this->cliente = addslashes($cliente);
            $this->prezzo = addslashes($prezzo);
            $this->id_foto = addslashes($foto);
        }
    }
?>
