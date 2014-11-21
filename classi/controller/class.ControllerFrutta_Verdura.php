<?php

    //AUTORE: Alex Vezzelli - alexsoluzioniweb.it

    class ControllerFrutta_Verdura{
        //definisco le proprietà
        private $c_db;
        private $c_frutta_verdura;
        
        //definisco il costruttore
        public function __construct(Frutta_Verdura $fv){
            $this->c_db = $fv->getDB();
            $this->c_frutta_verdura = $fv;
        }
        
        //definisco i metodi
        public function saveFrutta_Verdura(){
            //La funzione salva i valori di frutta e verdura nel database
            //preparo la query
            //$query = "INSERT INTO ".$this->c_db->getDatabase().".Frutta_Verdura (Tipologia_Prodotto, Nome_Prodotto, Prezzo, Unita)
            //          VALUES ('".$this->c_frutta_verdura->getTipologia()."', '".$this->c_frutta_verdura->getNome()."', '".$this->c_frutta_verdura->getPrezzo()."', '".$this->c_frutta_verdura->getUnita()."')";
            //$query = utf8_encode($query);
           // echo '<br><br>'.$query.'<br><br>';
            try{                
                $this->c_db->insert( $this->c_db->prefix.'Frutta_Verdura', 
                                     array( 'Tipologia_Prodotto' => $this->c_frutta_verdura->getTipologia(), 
                                            'Nome_Prodotto' => utf8_encode($this->c_frutta_verdura->getNome()),
                                            'Prezzo' => $this->c_frutta_verdura->getPrezzo(),
                                            'Unita' => $this->c_frutta_verdura->getUnita(),
                                            'ID_Foto' => $this->c_frutta_verdura->getID_Foto()), 
                                     array('%s', '%s', '%s', '%s', '%s')
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
