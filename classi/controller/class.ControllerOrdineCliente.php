<?php

    //AUTORE: Alex Vezzelli - alexsoluzioniweb.it
    
    class ControllerOrdineCliente{
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
        public function  saveOrdineCliente(){
            //La funzione salva i valori di Ordine Cliente nel database
            try{
                $this->wpdb->insert($this->table,
                                array('ID_Cliente' => addslashes($this->ordine_cliente->getID_Cliente()),
                                      'ID_Ordine' => addslashes($this->ordine_cliente->getID_Ordine()),
                                      'Costo_Totale' => addslashes($this->ordine_cliente->getCostoTotale()),
                                      'Data_Ordine' => addslashes($this->ordine_cliente->getDataOrdine()),
                                      'Note' => addslashes($this->ordine_cliente->getNote())
                                ),
                                array('%s', '%s', '%s', '%s', '%s')
                        );
                return true;
            }
            catch(Exception $e){
                _e($e);
                return false;
            }
        } 
        
       
        public function setID_Cliente($id, $id_cliente){
            try{
                $this->wpdb->update($this->table,
                            array('ID_Cliente' => addslashes($id_cliente)),
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
        public function setID_Ordine($id, $id_ordine){
            try{
                $this->wpdb->update($this->table,
                            array('ID_Ordine' => addslashes($id_ordine)),
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
        public function setCostoTotale($id, $costo){
            try{
                $this->wpdb->update($this->table,
                            array('Costo_Totale' => addslashes($costo)),
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
        
         public function setSconto($id, $sconto){
             try{
                $this->wpdb->update($this->table,
                            array('Sconto' => addslashes($sconto)),
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
        
        public function setCostoFinale($id, $costo){
            try{
                $this->wpdb->update($this->table,
                            array('Costo_Finale' => addslashes($costo)),
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
        
        public function setDataOrdine($id, $data){
            try{
                $this->wpdb->update($this->table,
                            array('Data_Ordine' => addslashes($data)),
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
        public function setNote($id, $note){
            try{
                $this->wpdb->update($this->table,
                            array('Note' => addslashes($note)),
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
