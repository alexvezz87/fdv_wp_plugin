<?php

    //AUTORE: Alex Vezzelli - alexsoluzioniweb.it

    class ViewArticoloOrdine{
        //definisco gli attributi
        private $articolo_ordine;
        private $wpdb;
        private $table;
        
        //definisco il costruttore
        public function __construct(ArticoloOrdine $ao){
            global $wpdb;
            $wpdb->prefix = "wp_fdv_";
            $this->articolo_ordine = $ao;
            $this->wpdb = $wpdb;
            $this->table = $wpdb->prefix.'Articolo_Ordine';
        }
        
        //definisco i metodi
        
        
        protected function getPrezzoUnitario($id){
            //La funzione restituisce il prezzo unitario conoscendo l'ID
            try{
                $result = $this->wpdb->get_var($this->wpdb->prepare(
                                "SELECT Prezzo_Unitario
                                 FROM ".$this->table."
                                 WHERE ID = %d",
                                addslashes($id)));
                return stripslashes($result);
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
        }
        
        protected function getNomeArticolo($id){
            try{
                $result = $this->wpdb->get_var($this->wpdb->prepare(
                                "SELECT Nome_Articolo
                                 FROM ".$this->table."
                                 WHERE ID = %d",
                                addslashes($id)));
                return stripslashes($result);
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
        }
        
        protected function getUnitaMisura($id){
            try{
                $result = $this->wpdb->get_var($this->wpdb->prepare(
                                "SELECT unita_misura 
                                 FROM ".$this->table."
                                 WHERE ID = %d",
                                addslashes($id)));
                return stripslashes($result);
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
        }
        
        protected function getQuantita($id){
            //La funzione restituisce la quantita, conoscendo l'ID
            try{
                $result = $this->wpdb->get_var($this->wpdb->prepare(
                                "SELECT Quantita
                                 FROM ".$this->table."
                                 WHERE ID = %d",
                                addslashes($id)));
                return stripslashes($result);
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
        }
        
        protected function getID_Prodotto($id){
            //La funzione ritorna id_prodotto conoscendo l'identificativo
            try{
                $result = $this->wpdb->get_var($this->wpdb->prepare(
                                "SELECT ID_Prodotto
                                 FROM ".$this->table."
                                 WHERE ID = %d",
                                addslashes($id)));
                return stripslashes($result);
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
        }
        
        protected function getID_Tabella($id){
            //La funzione ritorna id tabella conoscendo l'identificativo
            try{
                $result = $this->wpdb->get_var($this->wpdb->prepare(
                                "SELECT ID_Tabella
                                 FROM ".$this->table."
                                 WHERE ID = %d",
                                addslashes($id)));
                return stripslashes($result);
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
        }
        
        protected function getCP($id){
            //La funzione ritorna id tabella conoscendo l'identificativo
            try{
                $result = $this->wpdb->get_var($this->wpdb->prepare(
                                "SELECT CP
                                 FROM ".$this->table."
                                 WHERE ID = %d",
                                addslashes($id)));
                return stripslashes($result);
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
        }
        
        protected function getID_Ordine($id){
            //La funzione ritorna id ordine conoscendo l'identificativo
            try{
                $result = $this->wpdb->get_var($this->wpdb->prepare(
                                "SELECT ID_Ordine
                                 FROM ".$this->table."
                                 WHERE ID = %d",
                                addslashes($id)));
                return stripslashes($result);
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
        }
        
                
        public function getID($id_prodotto, $id_tabella, $id_ordine){
            //La funzione restituisce ID specifico conoscendo i diversi ID che la compongono
            try{
                $result = $this->wpdb->get_var($this->wpdb->prepare(
                                "SELECT ID
                                 FROM ".$this->table."
                                 WHERE ID_Prodotto = %d
                                 AND ID_Tabella = %d
                                 AND ID_Ordine = %d",
                                 $id_prodotto,
                                 $id_tabella,
                                 $id_ordine));
                return stripslashes($result);
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
        }
        
        public function get_IDs_From_Ordine($id_ordine){
            //La funzione ritorna tutti gli ID di un ordine cliente
            try{
                $result = $this->wpdb->get_col($this->wpdb->prepare(
                                "SELECT ID
                                 FROM ".$this->table."
                                 WHERE ID_Ordine = %d",
                                 $id_ordine
                        ));
                return $result;
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
            
        }
        
        public function getArticolo_Ordine($id){
            //La funzione ritorna un oggetto di Articolo_Ordine conoscendo l'ID
            try{
                $temp = new ArticoloOrdine($this->getID_Ordine($id), $this->getID_Tabella($id), $this->getID_Prodotto($id));
                $temp->setCassettaPersonalizzata($this->getCP($id));
                $temp->setQuantita($this->getQuantita($id));
                $temp->setPrezzoUnitario($this->getPrezzoUnitario($id));
                $temp->setNomeArticolo($this->getNomeArticolo($id));
                $temp->setUnitaMisura($this->getUnitaMisura($id));
                
                return $temp;
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
        }
        
        
        
        
    }
?>
