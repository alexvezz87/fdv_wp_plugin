<?php

        //AUTORE: Alex Vezzelli - alexsoluzioniweb.it
        
        class Frutta_Verdura{
            //definisco le proprietà
            private $db;
            private $tipologia;
            private $nome;
            private $prezzo;
            private $unita;
            private $foto;
            
            //definisco il costruttore
            public function __construct($db){
                $this->db = $db;
            }
            
            //definisco i metodi
            public function getDB(){
                return $this->db;
            }
            public function getTipologia(){
                return $this->tipologia;
            }
            public function getNome(){
                return $this->nome;
            }
            public function getPrezzo(){
                return $this->prezzo;
            }
            public function getUnita(){
                return $this->unita;
            }
            public function getID_Foto(){
                return $this->foto;
            }
            
            public function setFruttaVerdura($tipologia, $nome, $prezzo, $unita, $foto){
                $this->tipologia = addslashes($tipologia);
                $this->nome = addslashes($nome);
                $this->prezzo = addslashes($prezzo);
                $this->unita = addslashes($unita);
                $this->foto = addslashes($foto);
            }
            
           
        }
?>
