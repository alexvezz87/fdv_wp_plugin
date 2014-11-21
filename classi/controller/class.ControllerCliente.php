<?php

    //AUTORE: Alex Vezzelli - alexsoluzioniweb.it

    class ControllerCliente{
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
        public function saveCliente(){
            //La funzione salva il cliente nel database
            try{
                
                //controllo il tipo di cliente che voglio inserire
                if($this->cliente->getTipoCliente() == 'A'){
                    //Azienda
                     $this->wpdb->insert($this->table, 
                             array('username' => addslashes($this->cliente->getUsername()),
                                   'email' => addslashes($this->cliente->getEmail()),
                                   'tipo' => $this->cliente->getTipoCliente(),
                                   'nominativo' => addslashes($this->cliente->getNominativo()),
                                   'partita_iva' => addslashes($this->cliente->getPartitaIva())),
                             array('%s', '%s', '%s', '%s', '%s')
                       );
                     return true;
                }
                else if($this->cliente->getTipoCliente() == 'P'){
                    //Privato
                    
                    $this->wpdb->insert($this->table, 
                             array('username' => addslashes($this->cliente->getUsername()),
                                   'email' => addslashes($this->cliente->getEmail()),
                                   'tipo' => $this->cliente->getTipoCliente(),
                                   'nominativo' => addslashes($this->cliente->getNominativo()),                                   
                                   'codice_fiscale' => addslashes($this->cliente->getCodiceFiscale())),
                             array('%s', '%s', '%s', '%s', '%s')
                       );
                    return true;
                }
                else{
                    //Non definito
                    return -1;
                }
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
        }
        
        public function setEmail($username, $email){
            //La funzione aggiorna il valore della mail conoscendo lo username
            try{
                $this->wpdb->update($this->table,
                        array('email' => $email),
                        array('username' => $username),
                        array('%s'),
                        array('%s'));
                return true;
            }
            catch(Exception $e){
                _e($e);
                return false;
            }            
        }
        
        protected function setTipoCliente($email, $tipo){
            //La funzione aggiorna la tipologia di utente
            try{
                $this->wpdb->update($this->table,
                        array('tipo' => $tipo),
                        array('email' => $email),
                        array('%s'),
                        array('%s'));
                return true;
            }
            catch(Exception $e){
                _e($e);
                return false;
            }            
        }
        public function setNominativo($email, $nominativo){
            //La funzione aggiorna il Nome
            
            try{
                $this->wpdb->update($this->table,
                        array('nominativo' => addslashes($nominativo)),
                        array('email' => $email),
                        array('%s'),
                        array('%s'));
                return true;
            }
            catch(Exception $e){
                _e($e);
                return false;
            }      
        }
        
        protected function setCodiceFiscale($email, $cf){
            //La funzione aggiorna il codice fiscale
            try{
                $this->wpdb->update($this->table,
                        array('codice_fiscale' => addslashes($cf)),
                        array('email' => $email),
                        array('%s'),
                        array('%s'));
                return true;
            }
            catch(Exception $e){
                _e($e);
                return false;
            }      
        }
        
        protected function setPartitaIva($username, $piva){
            //La funzione aggiorna la partita iva
            try{
                $this->wpdb->update($this->table,
                        array('partita_iva' => addslashes($piva)),
                        array('email' => $email),
                        array('%s'),
                        array('%s'));
                return true;
            }
            catch(Exception $e){
                _e($e);
                return false;
            }      
        }
        
        public function setClientePrivato($email, $nome, $cognome, $cf){
            //La funzione aggiorna il cliente a tipo Privato con i relativi campi
            if($this->setTipoCliente($email, 'P') && $this->setNominativo($email, $cognome.' '.$nome) && $this->setCodiceFiscale($email, $cf)){
                return true;
            }
            else{
                return false;
            }
        }
        
        public function setClienteAzienda($email, $rs, $piva){
            //La funzione aggiorna il cliente a tipo Azienda con i relativi campi
            if($this->setTipoCliente($email, 'A') && $this->setNominativo($email, $rs) && $this->setPartitaIva($email, $piva)){
                return true;
            }
            else{
                return false;
            }
        }
        
        public function setSconto($email, $sconto){
            //La funzione aggiorna lo sconto di un cliente
            try{
                $this->wpdb->update($this->table,
                        array('sconto' => addslashes($sconto)),
                        array('email' => $email),
                        array('%s'),
                        array('%s'));
                return true;
            }
            catch(Exception $e){
                _e($e);
                return false;
            }      
        }
    }
?>
