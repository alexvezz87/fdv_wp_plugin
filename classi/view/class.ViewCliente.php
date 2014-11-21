<?php

    //AUTORE: Alex Vezzelli - alexsoluzioniweb.it
    
    class ViewCliente{
        //definisco gli attributi        
        private $cliente;
        private $wpdb;
        private $table;
        //definisco il costruttore
        public function __construct(Cliente $cliente){
            global $wpdb;
            $wpdb->prefix = "wp_fdv_";
            $this->cliente = $cliente;
            $this->wpdb = $wpdb;
            $this->table = $wpdb->prefix.'Cliente';
        }
        
        //definisco i metodi
        
        public function getEmailFromID($id){
            try{
                $result = $this->wpdb->get_var($this->wpdb->prepare(
                                "SELECT email
                                 FROM ".$this->table."
                                 WHERE ID = %d",
                                addslashes($id)));
                return $result;
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
        }
        
        public function getEmail($username){
            //La funzione restituisce la mail, conoscendo lo username di un cliente
            try{
                $result = $this->wpdb->get_var($this->wpdb->prepare(
                                "SELECT email
                                 FROM ".$this->table."
                                 WHERE username = %s",
                                addslashes($username)));
                return $result;
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
        }
        
        protected function getTipoCliente($email){
            //La funzione restituisce il tipo di Cliente, se Privato o Azienda
            try{
                $result = $this->wpdb->get_var($this->wpdb->prepare(
                                "SELECT tipo
                                 FROM ".$this->table."
                                 WHERE email = %s",
                                addslashes($email)));
                return $result;
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
        }
        public function getNominativo($email){
            //La funzione restituisce il nome
            try{
                $result = $this->wpdb->get_var($this->wpdb->prepare(
                                "SELECT nominativo
                                 FROM ".$this->table."
                                 WHERE email = %s",
                                 addslashes($email)));
                return stripslashes($result);
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
        }
        
        public function getNominativoByID($id){
            //La funzione mi restituisce un cliente cercandolo per ID
            try{
                $result = $this->wpdb->get_var($this->wpdb->prepare(
                                "SELECT nominativo
                                 FROM ".$this->table."
                                 WHERE ID = %d",
                                 $id));
                return stripslashes($result);
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
        }
        
                
        protected function getCodiceFiscale($email){
            //La funzione restituisce il codice fiscale
            try{
                $result = $this->wpdb->get_var($this->wpdb->prepare(
                                "SELECT codice_fiscale
                                 FROM ".$this->table."
                                 WHERE email = %s",
                                 addslashes($email)));
                return stripslashes($result);
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
        }
        
        protected function getPartitaIva($email){
            //La funzione restituisce la partita iva
            try{
                $result = $this->wpdb->get_var($this->wpdb->prepare(
                                "SELECT partita_iva
                                 FROM ".$this->table."
                                 WHERE email = %s",
                                 addslashes($email)));
                return stripslashes($result);
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
        }
        public function getSconto($email){
            //La funzione restituisce lo sconto
            try{              
                $result = $this->wpdb->get_var($this->wpdb->prepare(
                                "SELECT sconto
                                 FROM ".$this->table."
                                 WHERE email = %s",
                                 addslashes($email)));               
                return stripslashes($result);               
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
        }
        
        public function isCliente($email){
            //la funzione indica se il cliente è presente nel database
            try{
                $result = $this->wpdb->get_var($this->wpdb->prepare(
                                "SELECT ID
                                 FROM ".$this->table."
                                 WHERE email = %s",
                                 addslashes($email)));
                return stripslashes($result);
            }
            catch(Exception $e){
                _e($e);
                return -1;
            }
        }
        
        
        public function getCliente($email){
            //La funzione restituisce un oggetto Cliente determinato dalla mail passata
            $tipo = $this->getTipoCliente($email);
            
            $sconto = $this->getSconto($email);
            $temp_cliente = new Cliente(null, $email);
            $temp_cliente->setID($this->isCliente($email));
            if($sconto != false && $sconto != null){
                $temp_cliente->setSconto($sconto);
            }
            
            if($tipo == 'A'){
                //Azienda
                $rs = $this->getNominativo($email);
                $piva = $this->getPartitaIva($email);
                if($rs != false && $piva != false){
                    $temp_cliente->setDatiAzienda($rs, $piva);
                    return $temp_cliente;                    
                }     
                
            }
            else if($tipo == 'P'){
                //Privato
               
                $nominativo = $this->getNominativo($email);
                $cf = $this->getCodiceFiscale($email);
                 
                if($nominativo != false){
                    $temp_cliente->setDatiPrivato($nominativo, $cf);     
                    
                    return $temp_cliente;
                }
            }
            else{
                //Non definito 
                
            }
            return -1;
        }
        
                
        public function getUsernames(){
            //la funzione restituisce tutte le mail
            try{
                 $result = $this->wpdb->get_col( $this->wpdb->prepare( 
                        "
                                SELECT username
                                FROM ".$this->table."                                
                        ", 
                        1
                ) );
                return $result;
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
        }
        
        public function getEmails(){
            //la funzione restituisce tutte le mail
          
            try{
                 $result = $this->wpdb->get_col( $this->wpdb->prepare( 
                        "
                                SELECT email
                                FROM ".$this->table."                                
                        ", 
                        1
                ) );
                  
                return $result;
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
        }
        
        public function getClienti(){
            //La funzione restituisce un array di oggetto Cliente
            $emails = $this->getEmails();
            
            if($emails != false){
                //ho dei clienti
                $clienti = array();
                $i=0;
                while($i < count($emails)){
                    $cliente = $this->getCliente($emails[$i]);
                    if($cliente != -1){
                        array_push($clienti, $cliente);
                    }
                    $i++;
                }
                return $clienti;
            }
            return -1;
        }
        
        
    }
?>
