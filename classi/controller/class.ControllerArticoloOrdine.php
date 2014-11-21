<?php

    //AUTORE: Alex Vezzelli - alexsoluzioniweb.it

    class ControllerArticoloOrdine{
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
        public function saveArticolo_Ordine(){
            //La funzione salva i valori di Articolo_Ordine nel database
            try{
                $this->wpdb->insert($this->table,
                                array('ID_Prodotto' => addslashes($this->articolo_ordine->getID_Prodotto()),
                                      'ID_Tabella' => addslashes($this->articolo_ordine->getID_Tabella()),
                                      'ID_Ordine' => addslashes($this->articolo_ordine->getID_Ordine()),
                                      'CP' => addslashes($this->articolo_ordine->getCassettaPersonalizzata()),
                                      'Quantita' => addslashes($this->articolo_ordine->getQuantita()),
                                      'Prezzo_Unitario' => addslashes($this->articolo_ordine->getPrezzoUnitario()),
                                      'Nome_Articolo' => addslashes($this->articolo_ordine->getNomeArticolo()),
                                      'unita_misura' => addslashes($this->articolo_ordine->getUnitaMisura())
                                ),
                                array('%d', '%d', '%d', '%d', '%s', '%s', '%s', '%s')
                        );
                return true;
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
        }
        
        public function setQuantita($id){
            //La funzione setta la quantità dato un determinato id
            try{
                $this->wpdb->update($this->table,
                            array('Quantita' => addslashes($id)),
                            array('ID' => $id),
                            array('%s'),
                            array('%d'));
                return true;
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
        }
        
        public function setPrezzoUnitario($id){
            //La funzione setta il prezzo unitario dato un determinato ID
            try{
                $this->wpdb->update($this->table,
                            array('Prezzo_Unitario' => addslashes($id)),
                            array('ID' => $id),
                            array('%s'),
                            array('%d'));
                return true;
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
        }
    }
?>
