<?php

    //AUTORE: Alex Vezzelli - alexsoluzioniweb.it

    class ControllerCassetta{
        //definisco le proprietà
        private $c_cassetta;
        private $c_db;
        
        //definisco il costruttore
        public function __construct(Cassetta $cassetta){
            $this->c_cassetta = $cassetta;
            $this->c_db = $cassetta->getDB();
        }
        
        public function saveCassetta(){
            //La funzione salva i valori di cassetta nel database
            //preparo la query
            // $query = "INSERT INTO ".$this->c_db->getDatabase().".Cassetta (Tipologia_Cassetta, Num_Prodotti, Peso, Cliente, Prezzo)
            //           VALUES('".$this->c_cassetta->getTipologiaCassetta()."', '".$this->c_cassetta->getNumProdotti()."', '".$this->c_cassetta->getPeso()."', '".$this->c_cassetta->getCliente()."', '".$this->c_cassetta->getPrezzo()."')";
           
            //$query = utf8_encode($query);
            try{                
                $this->c_db->insert( $this->c_db->prefix.'Cassetta', 
                                     array( 'Tipologia_Cassetta' => $this->c_cassetta->getTipologiaCassetta(), 
                                            'Num_Prodotti' => $this->c_cassetta->getNumProdotti(),
                                            'Peso' => $this->c_cassetta->getPeso(),
                                            'Cliente' => $this->c_cassetta->getCliente(),
                                            'Prezzo' => $this->c_cassetta->getPrezzo(),
                                            'ID_Foto' => $this->c_cassetta->getID_Foto()), 
                                     array('%s', '%s', '%s', '%s', '%s', '%s')
                );
                              
                return true;
            }
            catch(Exception $e){
                echo $e;
                return false;
            }
        }
    }
?>
