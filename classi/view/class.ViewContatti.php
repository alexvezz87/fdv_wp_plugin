<?php

    //AUTORE: Alex Vezzelli - alexsoluzioniweb.it
    
    class ViewContatti{
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
        
        public function getAllContatti(){
            $emails = $this->getEmails();
            if($emails != false){
                $contatti = array();
                $i=0;
                while($i < count($emails)){
                    $contatto = $this->getContatti($emails[$i]);
                    if($contatto != -1){
                        array_push($contatti, $contatto);
                    }
                    $i++;
                }
                return $contatti;
            }
            return -1;
        }
       
        public function getEmails(){
            //la funzione restituisce tutte le mail
            try{
                 $result = $this->v_db->get_col( $this->v_db->prepare( 
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
        
               
        public function isContatto($email){
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
        
        public function getContatti($email){
            //La funzione ritorna un oggetto Contatti
            try{
                $temp_contatti = new Contatti($email);
                //includo l'indirizzo di fatturazione
                $ind_fatt = $this->getIndirizzoFatturazione($email);
                $temp_contatti->setIndirizzoFatturazione(stripslashes($ind_fatt['Via']), stripslashes($ind_fatt['Civico']), stripslashes($ind_fatt['CAP']), stripslashes($ind_fatt['Citta']), stripslashes($ind_fatt['Prov']));
                //includo l'indirizzo di spedizione
                $ind_sped = $this->getIndirizzoSpedizione($email);
                
                $temp_contatti->setIndirizzoSpedizione($ind_sped);
                //includo gli altri contatti telefonici
                $temp_contatti->setTelefono($this->getTelefono($email));
                $temp_contatti->setCellulare($this->getCellulare($email));
                $temp_contatti->setFax($this->getFax($email));
                return $temp_contatti;
            }
            catch(Exception $e){
                _e($e);
                return -1;
            }
           
        }
        
        public function getTelefono($email){
             try{
                $result = $this->wpdb->get_var($this->wpdb->prepare(
                                "SELECT telefono
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
        public function getCellulare($email){
             try{
                $result = $this->wpdb->get_var($this->wpdb->prepare(
                                "SELECT cellulare
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
        public function getFax($email){
             try{
                $result = $this->wpdb->get_var($this->wpdb->prepare(
                                "SELECT fax
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
        
        public function getIndirizzoFatturazione($email){
            //La funzione ritorna un array di Indirizzo
            $indirizzo = array();
            $indirizzo['Via'] = $this->getIndirizzoFatturazioneVia($email);
            $indirizzo['Civico'] = $this->getIndirizzoFatturazioneCivico($email);
            $indirizzo['CAP'] = $this->getIndirizzoFatturazioneCAP($email);
            $indirizzo['Citta'] = $this->getIndirizzoFatturazioneCitta($email);
            $indirizzo['Prov'] = $this->getIndirizzoFatturazioneProvincia($email);
            
            return $indirizzo;
        }
        
        protected function getIndirizzoFatturazioneVia($email){
            try{
                $result = $this->wpdb->get_var($this->wpdb->prepare(
                                "SELECT ind_fatt_via
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
        protected function getIndirizzoFatturazioneCivico($email){
            try{
                $result = $this->wpdb->get_var($this->wpdb->prepare(
                                "SELECT ind_fatt_civ
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
        protected function getIndirizzoFatturazioneCAP($email){
            try{
                $result = $this->wpdb->get_var($this->wpdb->prepare(
                                "SELECT ind_fatt_cap
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
        protected function getIndirizzoFatturazioneCitta($email){
            try{
                $result = $this->wpdb->get_var($this->wpdb->prepare(
                                "SELECT ind_fatt_cit
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
        protected function getIndirizzoFatturazioneProvincia($email){
            try{
                $result = $this->wpdb->get_var($this->wpdb->prepare(
                                "SELECT ind_fatt_prv
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
        
        public function getIndirizzoSpedizione($email){
            //La funzione ritorna un array di Indirizzo
            $indirizzo = array();
            $indirizzo['Via'] = $this->getIndirizzoSpedizioneVia($email);
            $indirizzo['Civico'] = $this->getIndirizzoSpedizioneCivico($email);
            $indirizzo['CAP'] = $this->getIndirizzoSpedizioneCAP($email);
            $indirizzo['Citta'] = $this->getIndirizzoSpedizioneCitta($email);
            $indirizzo['Prov'] = $this->getIndirizzoSpedizioneProvincia($email);
            
            return $indirizzo;
        }
        
        protected function getIndirizzoSpedizioneVia($email){
            try{
                $result = $this->wpdb->get_var($this->wpdb->prepare(
                                "SELECT ind_sped_via
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
        protected function getIndirizzoSpedizioneCivico($email){
            try{
                $result = $this->wpdb->get_var($this->wpdb->prepare(
                                "SELECT ind_sped_civ
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
        protected function getIndirizzoSpedizioneCAP($email){
            try{
                $result = $this->wpdb->get_var($this->wpdb->prepare(
                                "SELECT ind_sped_cap
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
        protected function getIndirizzoSpedizioneCitta($email){
            try{
                $result = $this->wpdb->get_var($this->wpdb->prepare(
                                "SELECT ind_sped_cit
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
        protected function getIndirizzoSpedizioneProvincia($email){
            try{
                $result = $this->wpdb->get_var($this->wpdb->prepare(
                                "SELECT ind_sped_prv
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
    }
?>
