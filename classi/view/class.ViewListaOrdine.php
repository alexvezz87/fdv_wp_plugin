<?php

    //AUTORE: Alex Vezzelli - alexsoluzioniweb.it

    class ViewListaOrdine{
        //definisco gli attributi
        private $v_db;
        private $v_l_ordine;
        
        //definisco il costruttore
        public function __construct(ListaOrdine $l_ordine){
            $this->v_db = $l_ordine->getDB();
            $this->v_l_ordine = $l_ordine;
        }
        
        //definisco i metodi
        public function getIDUltimoOrdine(){
            //La funzione ritorna l'ID dell'ultimo ordine aperto
            //imposto la query
           // $query = "SELECT MAX(ID) FROM ".$this->v_db->getDatabase().".Ordine";
            try{
               
                // imposta meta_key con il valore appropriato
                $id = 'ID';
                $lastID= $this->v_db->get_var( $this->v_db->prepare( 
                            "SELECT MAX(ID) 
                             FROM ".$this->v_db->prefix."Ordine", 
                            $id
                ) );
                return $lastID;
            }
            catch(Exception $e){
                echo $e;
                return false;
            }
        }
        
        public function getIDOrdineAperto(){
            //La funzione resituisce un array di ID di ordini aperti
            //imposto la query
            //$query = "SELECT ID 
            //          FROM ".$this->v_db->getDatabase().".Ordine
            //          WHERE Ordine_Aperto = '1'";
            try{
                 $ids = $this->v_db->get_col( $this->v_db->prepare( 
                        "
                                SELECT ID
                                FROM ".$this->v_db->prefix."Ordine 
                                WHERE Ordine_Aperto = %d
                        ", 
                        1
                ) );
                return $ids;
            }
            catch(Exception $e){
                echo $e;
                return false;
            }
        }
        
        public function getIDOrdini(){
            //La funzione resituisce tutti gli id degli ordini presenti nella tabella
            //imposto la query
            
            try{
                 $ids = $this->v_db->get_col( $this->v_db->prepare( 
                        "
                                SELECT ID
                                FROM ".$this->v_db->prefix."Ordine 
                                ORDER BY ID DESC
                                LIMIT 2
                        ", 
                        1
                ) );
                return $ids;
            }
            catch(Exception $e){
                echo $e;
                return false;
            }
        }
        
        public function getApertura_Ordine($id){
            //La funzione resituisce l'orario di apertura di un determinato ordine
            //imposto la query
           /* $query = "SELECT Apertura_Ordine
                      FROM ".$this->v_db->getDatabase().".Ordine
                      WHERE ID = $id";*/
            try{
                 $status = $this->v_db->get_var( $this->v_db->prepare( 
                        "
                                SELECT Apertura_Ordine
                                FROM ".$this->v_db->prefix."Ordine 
                                WHERE ID = %d
                        ", 
                        $id
                ) );
                return $status;
            }
            catch(Exception $e){
                echo $e;
                return false;
            }
        }
        
         public function getChiusura_Ordine($id){
            //La funzione resituisce l'orario di apertura di un determinato ordine
            //imposto la query
           /* $query = "SELECT Chiusura_Ordine
                      FROM ".$this->v_db->getDatabase()."Ordine
                      WHERE ID = $id";*/
            try{
                 $status = $this->v_db->get_var( $this->v_db->prepare( 
                        "
                                SELECT Chiusura_Ordine
                                FROM ".$this->v_db->prefix."Ordine 
                                WHERE ID = %d
                        ", 
                        $id
                ) );
                return $status;
            }
            catch(Exception $e){
                echo $e;
                return false;
            }
        }
        
        public function getOrdineStatus($id){
            //La funzione resituisce lo stato dell'ordine aperto/chiuso di un determinato ordine
            //imposto la query
        /*    $query = "SELECT Ordine_Aperto
                      FROM ".$this->v_db->getDatabase().".Ordine
                      WHERE ID = $id";
          */  
            try{
                
                $status = $this->v_db->get_var( $this->v_db->prepare( 
                        "
                                SELECT Ordine_Aperto 
                                FROM ".$this->v_db->prefix."Ordine 
                                WHERE ID = %d
                        ", 
                        $id
                ) );
                return $status;
                
            }
            catch(Exception $e){
                echo $e;
                return false;
            }
        }
    }
?>
