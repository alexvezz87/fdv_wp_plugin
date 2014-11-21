<?php

    //AUTORE: Alex Vezzelli - alexsoluzioniweb.it

    class ViewAggiunta_Cassetta{
        //definisco le proprietà
        private $v_db;
        private $v_aggiunta_cassetta;
        
        //definisco il costruttore
        public function __construct(Aggiunta_Cassetta $ag){
            $this->v_db = $ag->getDB();
            $this->v_aggiunta_cassetta = $ag;
            $this->v_db->prefix="wp_fdv_";
        }
        
        //definisco i metodi
        
        public function getAggiunta_Cassetta(){
            $ids = $this->getIDs();
            if($ids != false){
                $aggiunta_cassetta = array();
                $i=0;
                while($i < count($ids)){
                    $aggiunta_cassetta[$i] = $this->getItemAggiunta_Cassetta($ids[$i]);
                    $i++;
                }
                return $aggiunta_cassetta;
            }
            else{
                return false;
            }
        }
        
        public function getProdottiVetrina(){
            //La funzione ritorna un array contenente ID_Cassetta, Tipologia Cassetta, Prezzo, ID_Foto
            $query = "SELECT * FROM ".$this->v_db->prefix."Aggiunta_Cassetta";
            $result = $this->v_db->get_results($query);
            
            return $result;
        }
        public function getProdotto($id){
            $query = "SELECT * FROM ".$this->v_db->prefix."Aggiunta_Cassetta WHERE ID = $id";
            $result = $this->v_db->get_row($query);
            
            return $result;
        }
        
        public function getAggiunta_Cassetta_by_type($type){
            $ids = $this->getIDsByType($type);
            if($ids != false){
                $aggiunta_cassetta = array();
                $i=0;
                while($i < count($ids)){
                    $aggiunta_cassetta[$i] = $this->getItemAggiunta_Cassetta($ids[$i]);
                    $i++;
                }
                return $aggiunta_cassetta;
            }
            else{
                return false;
            }
        }
        
        public function getTypes(){
            //La funzione ritorna i tipi di prodotto di questa tabella
            try{
                 $result = $this->v_db->get_col( $this->v_db->prepare( 
                        "
                                SELECT DISTINCT Tipologia_Prodotto
                                FROM ".$this->v_db->prefix."Aggiunta_Cassetta
                                
                        ", 
                        1
                ) );
                return $result;
            }
            catch(Exception $e){
                echo $e;
                return false;
            }
        }
        
        public function getItemAggiunta_Cassetta($id){
            $errors = 0;
            if(!($tipologia = $this->getTipologiaProdotto($id))){$tipologia = ''; }
            if(!($nome = $this->getNomeProdotto($id))){$nome = '';}
            if(!($prezzo = $this->getPrezzo($id))){$prezzo = '';}
            if(!($unita = $this->getUnita($id))){$unita = '';}
            if(!($peso = $this->getPeso($id))){$peso = '';}
            if(!($note = $this->getNote($id))){$note = '';}
            if(!($foto = $this->getID_Foto($id))){$foto = '';}
            
            if($errors == 0){
                $aggiunta_cassetta = new Aggiunta_Cassetta($this->v_db);
                $aggiunta_cassetta->setAggiunta_Cassetta($tipologia, $nome, $prezzo, $unita, $peso, $note, $foto);                
                return $aggiunta_cassetta;
            }
            else{
                return false;
            }
        }
        
        public function getIDs(){
            //La funzione mi restituisce un array di ID, tutti quelli presenti nella tabella
            //imposto la query
            //$query = "SELECT ID 
            //          FROM ".$this->v_db->getDatabase().".Aggiunta_Cassetta";
            try{
                 $result = $this->v_db->get_col( $this->v_db->prepare( 
                        "
                                SELECT ID
                                FROM ".$this->v_db->prefix."Aggiunta_Cassetta
                                
                        ", 
                        1
                ) );
                return $result;
            }
            catch(Exception $e){
                echo $e;
                return false;
            }
        }
        
        public function getIDsByType($type){
            //La funzione ritorna gli id dei prodotti di una determinata tipologia
            try{
                 $result = $this->v_db->get_col( $this->v_db->prepare( 
                        "
                                SELECT ID
                                FROM ".$this->v_db->prefix."Aggiunta_Cassetta
                                WHERE Tipologia_Prodotto = %s
                                
                        ", 
                        $type
                ) );
                return $result;
            }
            catch(Exception $e){
                echo $e;
                return false;
            }
        }
        
        public function getTipologiaProdotto($id){
            //La funzione restituisce la tipologia prodotto di un prodotto indicato dall'ID
            //imposto la query
            //$query = "SELECT Tipologia_Prodotto
            //          FROM ".$this->v_db->getDatabase().".Aggiunta_Cassetta
            //          WHERE ID = $id";
            try{
                 $result = $this->v_db->get_var( $this->v_db->prepare( 
                        "
                                SELECT Tipologia_Prodotto
                                FROM ".$this->v_db->prefix."Aggiunta_Cassetta
                                WHERE ID = %d
                        ", 
                        $id
                ) );
                return $result;
            }
            catch(Exception $e){
                echo $e;
                return false;
            }
        }
        
        public function getIDfromNome($nome){
            //La funzione restituisce l'id del prodotto conoscendo il nome
            try{
                 $result = $this->v_db->get_var( $this->v_db->prepare( 
                        "
                                SELECT ID
                                FROM ".$this->v_db->prefix."Aggiunta_Cassetta
                                WHERE Nome_Prodotto = %s
                        ", 
                        $nome
                ) );
                return $result;
            }
            catch(Exception $e){
                echo $e;
                return false;
            }
        }
        
        public function getNomeProdotto($id){
            //La funzione restituisce la tipologia prodotto di un prodotto indicato dall'ID
            //imposto la query
            //$query = "SELECT Nome_Prodotto
            //          FROM ".$this->v_db->getDatabase().".Aggiunta_Cassetta
            //          WHERE ID = $id";
            try{
                 $result = $this->v_db->get_var( $this->v_db->prepare( 
                        "
                                SELECT Nome_Prodotto
                                FROM ".$this->v_db->prefix."Aggiunta_Cassetta
                                WHERE ID = %d
                        ", 
                        $id
                ) );
                return $result;
            }
            catch(Exception $e){
                echo $e;
                return false;
            }
        }
        
        public function getNome($id){
            return $this->getNomeProdotto($id);
        }
        
        public function getPrezzo($id){
            //La funzione restituisce la tipologia prodotto di un prodotto indicato dall'ID
            //imposto la query
            //$query = "SELECT Prezzo
            //          FROM ".$this->v_db->getDatabase().".Aggiunta_Cassetta
            //          WHERE ID = $id";
            try{
                 $result = $this->v_db->get_var( $this->v_db->prepare( 
                        "
                                SELECT Prezzo
                                FROM ".$this->v_db->prefix."Aggiunta_Cassetta
                                WHERE ID = %d
                        ", 
                        $id
                ) );
                return $result;
            }
            catch(Exception $e){
                echo $e;
                return false;
            }
        }
        
        public function getUnita($id){
            //La funzione restituisce la tipologia prodotto di un prodotto indicato dall'ID
            //imposto la query
            //$query = "SELECT Unita
            //          FROM ".$this->v_db->getDatabase().".Aggiunta_Cassetta
            //          WHERE ID = $id";
            try{
                 $result = $this->v_db->get_var( $this->v_db->prepare( 
                        "
                                SELECT Unita
                                FROM ".$this->v_db->prefix."Aggiunta_Cassetta
                                WHERE ID = %d
                        ", 
                        $id
                ) );
                return $result;
            }
            catch(Exception $e){
                echo $e;
                return false;
            }
        }
        
        public function getPeso($id){
            //La funzione restituisce la tipologia prodotto di un prodotto indicato dall'ID
            //imposto la query
            //$query = "SELECT Peso
            //          FROM ".$this->v_db->getDatabase().".Aggiunta_Cassetta
            //          WHERE ID = $id";
            try{
                 $result = $this->v_db->get_var( $this->v_db->prepare( 
                        "
                                SELECT Peso
                                FROM ".$this->v_db->prefix."Aggiunta_Cassetta
                                WHERE ID = %d
                        ", 
                        $id
                ) );
                return $result;
            }
            catch(Exception $e){
                echo $e;
                return false;
            }
        }
        
        public function getNote($id){
            //La funzione restituisce la tipologia prodotto di un prodotto indicato dall'ID
            //imposto la query
            //$query = "SELECT Note
            //          FROM ".$this->v_db->getDatabase().".Aggiunta_Cassetta
            //          WHERE ID = $id";
            try{
                 $result = $this->v_db->get_var( $this->v_db->prepare( 
                        "
                                SELECT Note
                                FROM ".$this->v_db->prefix."Aggiunta_Cassetta
                                WHERE ID = %d
                        ", 
                        $id
                ) );
                return $result;
            }
            catch(Exception $e){
                echo $e;
                return false;
            }
        }
        
        public function getID_Foto($id){
            try{
                 $result = $this->v_db->get_var( $this->v_db->prepare( 
                        "
                                SELECT ID_Foto
                                FROM ".$this->v_db->prefix."Aggiunta_Cassetta
                                WHERE ID = %d
                        ", 
                        $id
                ) );
                return $result;
            }
            catch(Exception $e){
                echo $e;
                return false;
            }
        }
    }
?>
