<?php

    //AUTORE: Alex Vezzelli - alexsoluzioniweb.it

    class ControllerAggiunta_Cassetta{
        //definisco le proprietà
        private $c_db;
        private $c_aggiunta_cassetta;
        
        //definisco il costruttore
        public function __construct(Aggiunta_Cassetta $ag){
            $this->c_db = $ag->getDB();
            $this->c_aggiunta_cassetta = $ag;
        }
        
        //definisco i metodi
        public function saveAggiunta_Cassetta(){
            //La funzione salva la tabella aggiunta cassetta nel database
            //preparo la query
            //$query = "INSERT INTO ".$this->c_db->getDatabase().".Aggiunta_Cassetta (Tipologia_Prodotto, Nome_Prodotto, Prezzo, Unita, Peso, Note)
            //          VALUES('".$this->c_aggiunta_cassetta->getTipologia()."', '".$this->c_aggiunta_cassetta->getNome()."', '".$this->c_aggiunta_cassetta->getPrezzo()."', '".$this->c_aggiunta_cassetta->getUnita()."', '".$this->c_aggiunta_cassetta->getPeso()."', '".$this->c_aggiunta_cassetta->getNote()."')";
            //$query = utf8_encode($query);
            try{                
               
                $this->c_db->insert( $this->c_db->prefix.'Aggiunta_Cassetta', 
                                     array( 'Tipologia_Prodotto' => utf8_encode($this->c_aggiunta_cassetta->getTipologia()), 
                                            'Nome_Prodotto' => utf8_encode($this->c_aggiunta_cassetta->getNome()),
                                            'Prezzo' => utf8_encode($this->c_aggiunta_cassetta->getPrezzo()),
                                            'Unita' => utf8_encode($this->c_aggiunta_cassetta->getUnita()),
                                            'Peso' => utf8_encode($this->c_aggiunta_cassetta->getPeso()),
                                            'Note' => utf8_encode($this->c_aggiunta_cassetta->getNote()),
                                            'ID_Foto' => utf8_encode($this->c_aggiunta_cassetta->getID_Foto())), 
                                     array('%s', '%s', '%s', '%s', '%s', '%s', '%s')
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
