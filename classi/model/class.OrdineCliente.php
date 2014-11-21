<?php

    //AUTORE: Alex Vezzelli - alexsoluzioniweb.it

    class OrdineCliente{
        //definisco gli attributi
        private $id_cliente; //identificativo del cliente (email)
        private $id_ordine;  //identificativo dell'ordine settimanale
        private $costo_totale; 
        private $data_ordine;
        private $note;
        
        //definisco il costruttore
        public function __construct($id_cliente, $id_ordine){
            $this->id_cliente = $id_cliente;
            $this->id_ordine = $id_ordine;
        }
        
        //definisco i metodi
        public function getID_Cliente(){
            return $this->id_cliente;
        }
        public function getID_Ordine(){
            return $this->id_ordine;
        }
        public function getCostoTotale(){
            return $this->costo_totale;
        }
        public function getDataOrdine(){
            return $this->data_ordine;
        }
        public function getNote(){
            return $this->note;
        }
        
        public function setID_Cliente($id){
            $this->id_cliente = $id;
        }
        public function setID_Ordine($id){
            $this->id_ordine = $id;
        }
        public function setCostoTotale($costo){
            $this->costo_totale = $costo;
        }
        public function setDataOrdine($data){
            $this->data_ordine = $data;
        }
        public function setNote($note){
            $this->note = $note;
        }
    }
?>
