<?php

    //AUTORE: Alex Vezzelli - alexsoluzioniweb.it

    class Contatti{
        //definisco gli attributi
        private $email;
        private $ind_fatt_via, $ind_fatt_civ, $ind_fatt_cap, $ind_fatt_cit, $ind_fatt_prv;
        private $ind_sped_via, $ind_sped_civ, $ind_sped_cap, $ind_sped_cit, $ind_sped_prv;
        private $telefono, $cellulare, $fax;
        
        //definisco il costruttore
        public function __construct($email){
            $this->email = $email;
        }
        
        //definisco i metodi
        public function getEmail(){
            return $this->email;
        }
        
        public function getIndirizzoFatturazione(){
            $indirizzo = array();
            $indirizzo['Via'] = $this->ind_fatt_via;
            $indirizzo['Civico'] = $this->ind_fatt_civ;
            $indirizzo['CAP'] = $this->ind_fatt_cap;
            $indirizzo['Citta'] = $this->ind_fatt_cit;
            $indirizzo['Prov'] = $this->ind_fatt_prv;
            
            return $indirizzo;
        }
        
        public function getIndirizzoSpedizione(){
            $indirizzo = array();
            $indirizzo['Via'] = $this->ind_sped_via;
            $indirizzo['Civico'] = $this->ind_sped_civ;
            $indirizzo['CAP'] = $this->ind_sped_cap;
            $indirizzo['Citta'] = $this->ind_sped_cit;
            $indirizzo['Prov'] = $this->ind_sped_prv;
            
            return $indirizzo;
        }
        
        public function getTelefono(){
            return $this->telefono;
        }
        public function getCellulare(){
            return $this->cellulare;
        }
        public function getFax(){
            return $this->fax;
        }
        
        public function setIndirizzoFatturazione($via, $civ, $cap, $cit, $prv){
            $this->ind_fatt_via = $via;
            $this->ind_fatt_civ = $civ;
            $this->ind_fatt_cap = $cap;
            $this->ind_fatt_cit = $cit;
            $this->ind_fatt_prv = $prv;
        }
        public function setIndirizzoSpedizione($indirizzo){
            
            $this->ind_sped_via = $indirizzo['Via'];
            $this->ind_sped_civ = $indirizzo['Civico'];
            $this->ind_sped_cap = $indirizzo['CAP'];
            $this->ind_sped_cit = $indirizzo['Citta'];
            $this->ind_sped_prv = $indirizzo['Prov'];
        }
        
        public function setTelefono($tel){
            $this->telefono = $tel;
        }
        public function setCellulare($cell){
            $this->cellulare = $cell;
        }
        public function setFax($fax){
            $this->fax = $fax;
        }
        
    }
?>
