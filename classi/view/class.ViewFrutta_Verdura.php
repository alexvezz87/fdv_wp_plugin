<?php

    //AUTORE: Alex Vezzelli - alexsoluzioniweb.it

    class ViewFrutta_Verdura{
        //definisco le proprietà
        private $v_db;
        private $v_frutta_verdura;
        
        //definisco il costruttore
        public function __construct(Frutta_Verdura $fv){
            $this->v_db = $fv->getDB();
            $this->v_frutta_verdura = $fv;
            $this->v_db->prefix="wp_fdv_";
        }
        
        //definisco i metodi        
        public function getFrutta_Verdura(){
            //La funzione restituisce un array di frutta_verdura
            $ids = $this->getIDs();
            if($ids != false){
                $frutta_verdura = array();
                $i=0;
                while($i < count($ids)){
                    $frutta_verdura[$i] = $this->getItemFrutta_Verdura($ids[$i]);
                    $i++;
                }
                return $frutta_verdura;
            }
            else{
                return false;
            }
        }
        
        public function getProdottiVetrina(){
            //La funzione ritorna un array contenente ID_Cassetta, Tipologia Cassetta, Prezzo, ID_Foto
            $query = "SELECT * FROM ".$this->v_db->prefix."Frutta_Verdura";
            $result = $this->v_db->get_results($query);
            
            return $result;
        }
        
        public function getProdotto($id){
            $query = "SELECT * FROM ".$this->v_db->prefix."Frutta_Verdura WHERE ID = $id";
            $result = $this->v_db->get_row($query);
            
            return $result;
        }
        
        public function getFrutta_Verdura_by_type($type){
            
            $ids = $this->getIDsByType($type);
           
            if($ids != false){
                $frutta_verdura = array();
                $i=0;
                while($i < count($ids)){
                    $frutta_verdura[$i] = $this->getItemFrutta_Verdura($ids[$i]);
                    $i++;
                }
                
                return $frutta_verdura;
            }
            else{
                return false;
            }
        }
        
        
        
        public function getItemFrutta_Verdura($id){
            //La funzione restituisce un oggetto Frutta_Verdura conoscendo l'ID
            $errors = 0;
            if(!($tipologia = $this->getTipologiaProdotto($id))){$errors++; echo '<br>errore in tipologia<br>';}
            if(!($nome = $this->getNomeProdotto($id))){$errors++; echo '<br>errore in nome prodotto<br>';}
            if(!($prezzo = $this->getPrezzo($id))){$errors++; echo '<br>errore in prezzo<br>';}
            if(!($unita = $this->getUnita($id))){$errors++; $unita = ''; $errors=0;}
            if(!($id_foto = $this->getID_Foto($id))){$errors++;}
              
            if($errors == 0){
                $frutta_verdura = new Frutta_Verdura($this->v_db);
                $frutta_verdura->setFruttaVerdura($tipologia, $nome, $prezzo, $unita, $id_foto);
                return $frutta_verdura;
            }
            else{
                return false;
            }
            
        }
        
        public function getIDsByType($type){
            //La funzione restituisce tutti gli ID cercando un determinato tipologia di frutta e verdura
             try{
                 
                 $result = $this->v_db->get_col( $this->v_db->prepare( 
                        "
                                SELECT ID
                                FROM ".$this->v_db->prefix."Frutta_Verdura
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
        
        public function getIDs(){
            //La funzione mi restituisce un array di ID, tutti quelli presenti nella tabella
            //imposto la query
            //$query = "SELECT ID 
            //          FROM ".$this->v_db->getDatabase().".Frutta_Verdura";
            try{
                 $result = $this->v_db->get_col( $this->v_db->prepare( 
                        "
                                SELECT ID
                                FROM ".$this->v_db->prefix."Frutta_Verdura
                                
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
        
        public function getTypes(){
            try{
                 $result = $this->v_db->get_col( $this->v_db->prepare( 
                        "
                                SELECT DISTINCT Tipologia_Prodotto
                                FROM ".$this->v_db->prefix."Frutta_Verdura
                                
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
        
        public function getIDfromName($nome){
            //La funzione ritorna l'ID conoscendo il nome passato
            try{
                 $result = $this->v_db->get_var( $this->v_db->prepare( 
                        "
                                SELECT ID
                                FROM ".$this->v_db->prefix."Frutta_Verdura
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
        
        public function getTipologiaProdotto($id){
            //La funzione restituisce la tipologia prodotto di un prodotto indicato dall'ID
            //imposto la query
            //$query = "SELECT Tipologia_Prodotto
            //          FROM ".$this->v_db->getDatabase().".Frutta_Verdura
            //          WHERE ID = $id";
            try{
                 $result = $this->v_db->get_var( $this->v_db->prepare( 
                        "
                                SELECT Tipologia_Prodotto
                                FROM ".$this->v_db->prefix."Frutta_Verdura
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
        
        public function getNomeProdotto($id){
            //La funzione restituisce il nome di un prodotto indicato dall'ID
            //imposto la query
            //$query = "SELECT Nome_Prodotto
            //          FROM ".$this->v_db->getDatabase().".Frutta_Verdura
            //          WHERE ID = $id";
            try{
                 $result = $this->v_db->get_var( $this->v_db->prepare( 
                        "
                                SELECT Nome_Prodotto
                                FROM ".$this->v_db->prefix."Frutta_Verdura
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
            //La funzione restituisce il prezzo di un prodotto indicato dall'ID
            //imposto la query
            //$query = "SELECT Prezzo
            //          FROM ".$this->v_db->getDatabase().".Frutta_Verdura
            //          WHERE ID = $id";
            try{
                 $result = $this->v_db->get_var( $this->v_db->prepare( 
                        "
                                SELECT Prezzo
                                FROM ".$this->v_db->prefix."Frutta_Verdura
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
            //La funzione restituisce l'unita di un prodotto indicato dall'ID
            //imposto la query
            
            //$query = "SELECT Unita
            //          FROM ".$this->v_db->getDatabase().".Frutta_Verdura
            //          WHERE ID = $id";
            try{
                 $result = $this->v_db->get_var( $this->v_db->prepare( 
                        "
                                SELECT Unita
                                FROM ".$this->v_db->prefix."Frutta_Verdura
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
                                FROM ".$this->v_db->prefix."Frutta_Verdura
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
