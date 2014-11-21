<?php

    //AUTORE: Alex Vezzelli - alexsoluzioniweb.it
    
    class ViewCassetta{
        //definisco le proprietà
        private $v_db;
        private $v_cassetta;
        
        
        //definisco il costruttore
        public function __construct(Cassetta $cassetta){
            $this->v_db = $cassetta->getDB();
            $this->v_cassetta = $cassetta;
            $this->v_db->prefix="wp_fdv_";
        }
        
        //definsco i metodi        
        public function getCassette(){
            //La funzione restituisce un array di cassette
            $ids = array();
            $ids = $this->getIDs();
            if($ids != false){
                $cassette = array();
                $i=0;
                while($i < count($ids)){
                    $cassette[$i] = $this->getCassetta($ids[$i]);
                    $i++;
                }
               return $cassette;
            }
            else{
                return false;
            }
        }
        
        public function getCassetteVetrina(){
            //La funzione ritorna un array contenente ID_Cassetta, Tipologia Cassetta, Prezzo, ID_Foto
            $query = "SELECT ID, Tipologia_Cassetta, Prezzo, ID_Foto FROM ".$this->v_db->prefix."Cassetta";
            $result = $this->v_db->get_results($query);
            
            return $result;
        }
        
        public function getProdotto($id){
            //La funzione ritorna una riga
            $query = "SELECT Tipologia_Cassetta, Prezzo, ID_Foto FROM ".$this->v_db->prefix."Cassetta WHERE ID = $id";
            $result = $this->v_db->get_row($query);
            
            return $result;
        }
        
        public function getCassetta($id){
            //La funzione restituisce un oggetto Cassetta, conoscendo l'ID            
            $errors = 0;
            if(!($tipologia = $this->getTipologiaCassetta($id))){ $errors++; }
            if(!($num_prodotti = $this->getNumProdotti($id))){ $errors++; }
            if(!($peso = $this->getPeso($id))){$errors++;}
            if(!($cliente = $this->getCliente($id))){ $errors++;}
            if(!($prezzo = $this->getPrezzo($id))){ $errors++;}
            if(!($id_foto = $this->getID_Foto($id))){ $errors++;}
            
            if($errors == 0){
                $cassetta = new Cassetta($this->v_db);
                $cassetta->setCassetta($tipologia, $num_prodotti, $peso, $cliente, $prezzo, $id_foto);
                return $cassetta;
            }
            else{
                return false;
            }
        }
        
        
        public function getIDs(){
            //La funzione restituisce un array di ID che corrispondono alle cassette presenti nella tabella
            //imposto la query
            //$query = "SELECT ID
            //          FROM ".$this->v_db->getDatabase().".Cassetta";
            try{
                 $ids = $this->v_db->get_col( $this->v_db->prepare( 
                        "
                                SELECT ID
                                FROM ".$this->v_db->prefix."Cassetta
                                
                        ", 
                        1
                ) );
                return $ids;
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
        }
        
        public function getID($tipologia){
            //La funzione mi restituisce l'ID dell'elemento cercando per tipologia
            
            //imposto la query
            //$query = "SELECT ID
            //          FROM ".$this->v_db->getDatabase().".Cassetta
            //          WHERE Tipologia_Cassetta = '$tipologia'";
            try{
                 $id = $this->v_db->get_var( $this->v_db->prepare( 
                        "
                                SELECT ID
                                FROM ".$this->v_db->prefix."Cassetta
                                WHERE Tipologia_Cassetta = %s
                        ", 
                        $tipologia
                ) );
                return $id;
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
        }
                
        public function getTipologiaCassetta($id){
            //La funzione restituisce il valore di tipologia cassetta
            //imposto la query
            //$query = "SELECT Tipologia_Cassetta
            //          FROM ".$this->v_db->getDatabase().".Cassetta
            //          WHERE ID = $id";
            try{
                 $result = $this->v_db->get_var( $this->v_db->prepare( 
                        "
                                SELECT Tipologia_Cassetta
                                FROM ".$this->v_db->prefix."Cassetta
                                WHERE ID = %d
                        ", 
                        $id
                ) );
                return $result;
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
        }
        
        /**
         * Restituisce il nome del prodotto
         * @param type $id
         * @return type
         */
        public function getNome($id){
            return $this->getTipologiaCassetta($id);
        }
        
        public function getNumProdotti($id){
            //La funzione resituisce il valore di Numero Prodotti
            //imposto la query
            //$query = "SELECT Num_Prodotti
            //          FROM ".$this->v_db->getDatabase().".Cassetta
            //          WHERE ID = $id";
            try{
                 $result = $this->v_db->get_var( $this->v_db->prepare( 
                        "
                                SELECT Num_Prodotti
                                FROM ".$this->v_db->prefix."Cassetta
                                WHERE ID = %d
                        ", 
                        $id
                ) );
                return $result;
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
        }
        
        public function getPeso($id){
            //La funzione resituisce il valore di Peso
            //imposto la query
            //$query = "SELECT Peso
            //          FROM ".$this->v_db->getDatabase().".Cassetta
            //          WHERE ID = $id";
            try{
                 $result = $this->v_db->get_var( $this->v_db->prepare( 
                        "
                                SELECT Peso
                                FROM ".$this->v_db->prefix."Cassetta
                                WHERE ID = %d
                        ", 
                        $id
                ) );
                return $result;
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
        }
        
        public function getCliente($id){
            //La funzione resituisce il valore di Cliente
            //imposto la query
            //$query = "SELECT Cliente
            //          FROM ".$this->v_db->getDatabase().".Cassetta
            //          WHERE ID = $id";
            try{
                 $result = $this->v_db->get_var( $this->v_db->prepare( 
                        "
                                SELECT Cliente
                                FROM ".$this->v_db->prefix."Cassetta
                                WHERE ID = %d
                        ", 
                        $id
                ) );
                return $result;
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
        }
        
        public function getPrezzo($id){
            //La funzione resituisce il valore di Prezzo
            //imposto la query
            //$query = "SELECT Prezzo
            //          FROM ".$this->v_db->getDatabase().".Cassetta
            //          WHERE ID = $id";
            try{
                 $result = $this->v_db->get_var( $this->v_db->prepare( 
                        "
                                SELECT Prezzo
                                FROM ".$this->v_db->prefix."Cassetta
                                WHERE ID = %d
                        ", 
                        $id
                ) );
                return $result;
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
        }
        
        public function getID_Foto($id){
            try{
                 $result = $this->v_db->get_var( $this->v_db->prepare( 
                        "
                                SELECT ID_Foto
                                FROM ".$this->v_db->prefix."Cassetta
                                WHERE ID = %d
                        ", 
                        $id
                ) );
                return $result;
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
        }
    }
?>
