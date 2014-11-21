<?php

    //AUTORE: Alex Vezzelli - alexsoluzioniweb.it
    
    class Cliente{
        //definisco gli attributi
        private $username;
        private $email;
        private $tipo_cliente;
        private $nominativo;
        private $codice_fiscale;        
        private $partita_iva;
        private $sconto;
        private $id;
        
        //definisco il costruttore
        public function __construct($username, $email){
            $this->username = $username;
            $this->email = $email;
        }
        
        //definisco i metodi
        public function getUsername(){
            return $this->username;
        }
        public function getEmail(){
            return $this->email;
        }
        public function getTipoCliente(){
            return $this->tipo_cliente;
        }        
        public function getNominativo(){
            return $this->nominativo;
        }        
        public function getCodiceFiscale(){
            return $this->codice_fiscale;
        }        
        public function getPartitaIva(){
            return $this->partita_iva;
        }        
        public function getSconto(){
            return $this->sconto;
        }
        public function getID(){
            return $this->id;
        }
        
        
        public function setID($id){
            $this->id = $id;
        }
        public function setUsername($username){
            $this->username = $username;
        }
        public function setEmail($email){
            $this->email = $email;
        }
        protected function setTipoCliente($tipo){
            $this->tipo_cliente = $tipo;
        }
        protected function setNominativo($nominativo){
            $this->nominativo = $nominativo;
        }        
        protected function setCodiceFiscale($cf){
            $this->codice_fiscale = $cf;
        }        
        protected function SetPartitaIva($piva){
            $this->partita_iva = $piva;
        }
        
        public function setSconto($sconto){
            $this->sconto = $sconto;
        }
        
        public function setDatiPrivato($nominativo, $cf){
            
            $this->setTipoCliente('P');
            $this->setNominativo($nominativo);            
            $this->setCodiceFiscale($cf);
        }
        
        public function setDatiAzienda($rs, $piva){
            $this->setTipoCliente('A');
            $this->setNominativo($rs);
            $this->SetPartitaIva($piva);
        }
        
        public function getDatiCliente(){
            $dati = array();
            $dati[0] = $this->getTipoCliente(); //tipo cliente (se Privato o Azienda)
            $dati[1] = $this->getSconto(); //sconto
            if($dati[0] == 'A'){
                //Azienda
                $dati[2] = $this->getNominativo(); //Ragione Sociale
                $dati[3] = $this->getPartitaIva(); //Partita Iva
            }
            else if($dati[0] == 'P'){
                //Privato
                $dati[2] = $this->getNominativo(); //Nome              
                $dati[3] = $this->getCodiceFiscale(); //Codice Fiscale
            }
            else{
                //non definito
                return -1;
            }
            return $dati;
        }
    }
?>
