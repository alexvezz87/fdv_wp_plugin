<?php

    //AUTORE: Alex Vezzelli - alexsoluzioniweb.it
    
    class ViewOrdineCliente{
        //definisco gli attributi
        private $ordine_cliente;
        private $wpdb;
        private $table;
        
        //definisco il costruttore
        public function __construct(OrdineCliente $oc){
            global $wpdb;
            $wpdb->prefix = "wp_fdv_";
            $this->ordine_cliente = $oc;
            $this->wpdb = $wpdb;
            $this->table = $wpdb->prefix.'Ordine_Cliente';
        }
        
        //definisco i metodi
        
        
        public function getSconto($id){
            //La funzione restituisce lo sconto di ArticoloOrdine
            try{
                $result = $this->wpdb->get_var($this->wpdb->prepare(
                                "SELECT Sconto "
                               . "FROM ".$this->table." "
                               . "WHERE ID = %d",
                                addslashes($id)));
               return stripslashes($result);
            } 
            catch (Exception $ex) {
                _e($ex);
                return false;
            }
        }
        
        protected function getNote($id){
            //La funzione restituisce le note di un determinato ordine
            try{
                $result = $this->wpdb->get_var($this->wpdb->prepare(
                                "SELECT Note
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
        
        protected function getDataOrdine($id){
            //La funzione restituisce la data di un determinato ordine
            try{
                $result = $this->wpdb->get_var($this->wpdb->prepare(
                                "SELECT Data_Ordine
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
        
        protected function getCostoTotale($id){
            //La funzione restituisce il costo totale di un determinato ordine
            try{
                $result = $this->wpdb->get_var($this->wpdb->prepare(
                                "SELECT Costo_Totale
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
        
        public function getIdFromData($data){
            try{
                $result = $this->wpdb->get_var($this->wpdb->prepare(
                                "SELECT ID
                                 FROM ".$this->table."
                                 WHERE Data_Ordine = %s",
                                addslashes($data)));
                return stripslashes($result);
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
        }
        
        public function getCostoFinale($id){
            try{
                $result = $this->wpdb->get_var($this->wpdb->prepare(
                                "SELECT Costo_Finale
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
        
        protected function getID_Cliente($id){
            //La funzione restituisce un id cliente di un determinato ordine
            try{
                $result = $this->wpdb->get_var($this->wpdb->prepare(
                                "SELECT ID_Cliente
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
            //La funzione restituisce l'identificativo settimanale di un ordine
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
        
        public function get_All_IDs(){
            //La funzione restituisce tutti gli id ordini presenti nel database
            try{
                $result = $this->wpdb->get_col($this->wpdb->prepare(
                                "SELECT  ID
                                 FROM ".$this->table."",
                                 1    
                        ));
                return $result;
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
        }
        
                
        public function get_IDs_From_Ordine($id_ordine){
            //La funzione restituisce tutti gli ordini di un determinato ordine
            try{
                $result = $this->wpdb->get_col($this->wpdb->prepare(
                                "SELECT ID
                                 FROM ".$this->table."
                                 WHERE ID_Ordine = %d
                                 ORDER BY ID DESC",
                                 $id_ordine
                        ));
                return $result;
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
        }
        
        public function get_IDs_From_Cliente($id_cliente){
            //La funzione restituisce tutti gli ID di ordini di un determinato cliente
             try{
                $result = $this->wpdb->get_col($this->wpdb->prepare(
                                "SELECT ID
                                 FROM ".$this->table."
                                 WHERE ID_Cliente = %d
                                 ORDER BY ID DESC",
                                 $id_cliente
                        ));
                return $result;
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
        }
        
        public function get_Last_ID_From_Cliente($id_cliente){
            //La funzione restituisce l'ultimo identificativo di ordine di un determinato cliente
            try{
                $result = $this->wpdb->get_var($this->wpdb->prepare(
                                "SELECT MAX(ID)
                                 FROM ".$this->table."
                                 WHERE ID_Cliente = %d",
                                 $id_cliente
                        ));
                return $result;
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
        }
        
        public function getOrdineCliente($id){
            //La funzione restituisce un oggetto Ordine_Cliente
            try{
                //creo un oggetto OrdineCliente
                $temp = new OrdineCliente($this->getID_Cliente($id), $this->getID_Ordine($id));
                $temp->setCostoTotale($this->getCostoTotale($id));
                $temp->setDataOrdine($this->getDataOrdine($id));
                $temp->setNote($this->getNote($id));
                
                return $temp;
            }
            catch(Exception $e){
                _e($e);
                return -1;
            }
        }
    }
?>
