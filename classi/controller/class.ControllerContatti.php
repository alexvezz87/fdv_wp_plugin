<?php

    //AUTORE: Alex Vezzelli - alexsoluzioniweb.it

    class ControllerContatti{
        //definisco gli attributi
        private $contatti;
        private $wpdb;
        private $table;
        
        //definisco il costruttore
        public function __construct(Contatti $contatti){
            global $wpdb;
            $wpdb->prefix = "wp_fdv_";
            $this->contatti = $contatti;
            $this->wpdb = $wpdb;
            $this->table = $wpdb->prefix.'Contatti';
        }
        
        //definisco i metodi
        public function saveContatti(){
            //La funzione salva i valori dei contatti nel database
            try{
                
                //inserisco un primo elemento e poi aggiorno
                $this->wpdb->insert($this->table, 
                             array('email' => addslashes($this->contatti->getEmail())),
                             array('%s')
                       );
                //faccio l'update degli altri contatti
                //aggiorno l'indirizzo di fatturazione 
                $ind_fatt = $this->contatti->getIndirizzoFatturazione();
                $this->setIndirizzoFatturazione($this->contatti->getEmail(), $ind_fatt['Via'], $ind_fatt['Civico'], $ind_fatt['CAP'], $ind_fatt['Citta'], $ind_fatt['Prov']);
                //aggiorno l'indirizzo di spedizione
                $ind_sped = $this->contatti->getIndirizzoSpedizione();
                $this->setIndirizzoSpedizione($this->contatti->getEmail(), $ind_sped);
                //aggiorno il telefono
                $this->setTelefono($this->contatti->getEmail(), $this->contatti->getTelefono());
                //aggiorno il cellulare
                $this->setCellulare($this->contatti->getEmail(), $this->contatti->getCellulare());
                //aggiorno il fax
                $this->setFax($this->contatti->getEmail(), $this->contatti->getFax());
                
                return true;
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
        }
        
        public function setEmail($id, $email){
             try{
                $this->wpdb->update($this->table,
                        array('email' => addslashes($email)),
                        array('ID' => $id),
                        array('%s'),
                        array('%d'));
                return true;
            }
            catch(Exception $e){
                _e($e);
                return false;
            }      
        }
        
        public function setTelefono($email, $telefono){
            try{
                $this->wpdb->update($this->table,
                        array('telefono' => addslashes($telefono)),
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
        public function setCellulare($email, $cellulare){
            try{
                $this->wpdb->update($this->table,
                        array('cellulare' => addslashes($cellulare)),
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
        public function setFax($email, $fax){
            try{
                
                $this->wpdb->update($this->table,
                        array('fax' => addslashes($fax)),
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
        
        public function setIndirizzoFatturazione($email, $via, $civ, $cap, $cit, $prv){
            if($this->setIndirizzoFatturazioneVia($email, $via) && $this->setIndirizzoFatturazioneCivico($email, $civ) && $this->setIndirizzoFatturazioneCAP($email, $cap && $this->setIndirizzoFatturazioneCitta($email, $cit)) && $this->setIndirizzoFatturazioneProvincia($email, $prv)){
                return true;
            }
            else{
                return false;
            }
        }
        
        protected function setIndirizzoFatturazioneVia($email, $via){
            //Aggiorno l'indirizzo di fatturazione
            try{
                $this->wpdb->update($this->table,
                        array('ind_fatt_via' => addslashes($via)),
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
        protected function setIndirizzoFatturazioneCivico($email, $civ){
            //Aggiorno l'indirizzo di fatturazione
            try{
                $this->wpdb->update($this->table,
                        array('ind_fatt_civ' => addslashes($civ)),
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
        protected function setIndirizzoFatturazioneCAP($email, $cap){
            //Aggiorno l'indirizzo di fatturazione
            try{
                $this->wpdb->update($this->table,
                        array('ind_fatt_cap' => addslashes($cap)),
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
        protected function setIndirizzoFatturazioneCitta($email, $cit){
            //Aggiorno l'indirizzo di fatturazione
            try{
                $this->wpdb->update($this->table,
                        array('ind_fatt_cit' => addslashes($cit)),
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
        protected function setIndirizzoFatturazioneProvincia($email, $prv){
            //Aggiorno l'indirizzo di fatturazione
            try{
                $this->wpdb->update($this->table,
                        array('ind_fatt_prv' => addslashes($prv)),
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
        
        
        
        public function setIndirizzoSpedizione($email, $indirizzo){
            
            $via = $indirizzo['Via']; 
            $civ = $indirizzo['Civico'];
            $cap = $indirizzo['CAP'];
            $cit = $indirizzo['Citta'];
            $prv = $indirizzo['Prov'];
            if($this->setIndirizzoSpedizioneVia($email, $via) && $this->setIndirizzoSpedizioneCivico($email, $civ) && $this->setIndirizzoSpedizioneCAP($email, $cap) && $this->setIndirizzoSpedizioneCitta($email, $cit) && $this->setIndirizzoSpedizioneProvincia($email, $prv)){
                return true;
            }
            else{
                return false;
            }
        }
        
        protected function setIndirizzoSpedizioneVia($email, $via){
            //Aggiorno l'indirizzo di spedizione
            try{
                $this->wpdb->update($this->table,
                        array('ind_sped_via' => addslashes($via)),
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
        protected function setIndirizzoSpedizioneCivico($email, $civ){
            //Aggiorno l'indirizzo di spedizione
            try{
                $this->wpdb->update($this->table,
                        array('ind_sped_civ' => addslashes($civ)),
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
        protected function setIndirizzoSpedizioneCAP($email, $cap){
            //Aggiorno l'indirizzo di spedizione
            try{
                $this->wpdb->update($this->table,
                        array('ind_sped_cap' => addslashes($cap)),
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
        protected function setIndirizzoSpedizioneCitta($email, $cit){
            //Aggiorno l'indirizzo di spedizione
            try{
                $this->wpdb->update($this->table,
                        array('ind_sped_cit' => addslashes($cit)),
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
        protected function setIndirizzoSpedizioneProvincia($email, $prv){
            //Aggiorno l'indirizzo di spedizione
            try{
                $this->wpdb->update($this->table,
                        array('ind_sped_prv' => addslashes($prv)),
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
