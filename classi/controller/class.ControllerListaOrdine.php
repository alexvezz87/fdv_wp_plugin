<?php

    //AUTORE: Alex Vezzelli - alexsoluzioniweb.it

    class ControllerListaOrdine{
        //definisco le proprietà
        private $c_db;
        private $c_ordine;
        
        //definisco il costruttore
        public function __construct(ListaOrdine $ordine){
            $this->c_db = $ordine->getDB();
            $this->c_ordine = $ordine;
        }
        
        //definisco i metodi
        public function apriOrdine(){
            //La funzione modifica il database modificando i valori che rendono l'ordine "aperto"
            
            //imposto la query
            //$query = "INSERT INTO ".$this->c_db->getDatabase().".Ordine (Apertura_Ordine, Ordine_Aperto) VALUES (NOW(), 1)";
            
            try{                
                $this->c_db->insert( $this->c_db->prefix.'Ordine', 
                                     array( 'Apertura_Ordine' => date("Y-m-d H:i:s"), 'Ordine_Aperto' => 1 ), 
                                     array('%s', '%d')
                );
                              
                return true;
            }
            catch(Exception $e){
                echo $e;
                return false;
            }
            
        }
        
        public function chiudiOrdine($id_ordine){
            //La funzione modifica il database andando ad operare sui valori che rendono l'ordine "chiuso"
            //imposto la query
            /*$query = "UPDATE ".$this->c_db->getDatabase().".Ordine
                      SET Chiusura_Ordine = NOW(), Ordine_Aperto = 0
                      WHERE ID = $id_ordine";*/
            try{
                
                $this->c_db->update( $this->c_db->prefix.'Ordine',
                                     array('Chiusura_Ordine' => date("Y-m-d H:i:s"), 'Ordine_Aperto' => 0), 
                                     array( 'ID' => $id_ordine ), 
                                     array( '%s', '%d'), 
                                     array( '%d' ) 
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
