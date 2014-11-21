<?php

    //AUTORE: Alex Vezzelli - alexsoluzioniweb.it
    
    class Carrello{
        //definisco gli attributi
        private $wpdb;
        
        //definisco il costruttore
        public function __construct(){
            global $wpdb;
            $this->wpdb = $wpdb;
           
            if(isset($_SESSION['fdv_carrello'])){
                //se il carrello di sessione è già inizializzato, allora salvo i valori dentro alla variabile della classe
              
            }
            else{
                //se il carrello non è inizializzato, allora salvo i valori inizializzati nelle variabili di sessione
                $_SESSION['fdv_carrello'] = array();
                
                
            }
            
            
        }
        
        public function isCarrelloInizializzato(){
            //Funzione che mi dice se la variabile di sessione del carrello è inizializzata oppure no
            if(isset($_SESSION['fdv_carrello'])){
                return true;
            }
            else{
                return false;
            }
        }
        
               
        public function rimuoviCarrello(){
            if($this->isCarrelloInizializzato()){
                unset($_SESSION['fdv_carrello']);
            }
        }
        
        public function isCarrelloEmpty(){
            //Funzione che mi dice se il carrello è vuoto
            
            //Controllo se prima il carrello è inizializzato
            if($this->isCarrelloInizializzato()){
                if(count($_SESSION['fdv_carrello']) == 0){
                    return true;
                }
                else{
                    return false;
                }
            }
        }
        
        public function aggiungiArticolo(ArticoloOrdine $articolo_ordine){
            //prima di inserire un articolo, devo controllare se esso non è già presente.
            //se lo è, devo modificare la quantità indicata nel singolo ordine
            //se non lo è, lo aggiungo normalmente
            $temp_articolo_ordine = new ArticoloOrdine(null, null, null);
                       
            if($this->isCarrelloInizializzato()){
                //entro se il carrello c'è.. altrimenti non ha senso
                
                if($this->getDimensioneCarrello() > 0){
                    //se il carrello ha dentro qualcosa faccio un controllo sugli elementi presenti                    
                    $i=0;
                    $trovato = false;
                    while($i < $this->getDimensioneCarrello() && $trovato == false){
                   
                       $temp_articolo_ordine = $_SESSION['fdv_carrello'][$i]; 
                      
                       if($temp_articolo_ordine->getCassettaPersonalizzata() === $articolo_ordine->getCassettaPersonalizzata() && $temp_articolo_ordine->getID_Tabella() === $articolo_ordine->getID_Tabella() && $temp_articolo_ordine->getID_Prodotto() === $articolo_ordine->getID_Prodotto()){
                           //ho trovato una corrispondenza e quindi devo aggiungere i valori a quantità
                           $temp_articolo_ordine->setQuantita($temp_articolo_ordine->getQuantita() + $articolo_ordine->getQuantita());
                           //aggiorno la variabile di sessione
                           $_SESSION['fdv_carrello'][$i] = $temp_articolo_ordine;
                           $trovato = true;
                       }

                       $i++;
                    }

                    if($trovato == false){
                        
                        //se non ho trovato l'elemento che stavo cercando, allora lo aggiungo normalmente
                        try{

                            array_push($_SESSION['fdv_carrello'], $articolo_ordine);
                            return true;
                        }
                        catch(Exception $e){
                            _e($e);
                            return false;
                        }

                    }
                }
                else{
                    
                    //non ho elementi, aggiungo elemento
                    array_push($_SESSION['fdv_carrello'], $articolo_ordine);
                    return true;
                }
            }
            else{
                //carrello non inizializzato
                return -1;
            }
            
        }
        
        public function rimuoviArticolo($id_tabella, $id_prodotto){
            //La funzione rimuove un articolo dal carrello
            $temp_articolo_ordine = new ArticoloOrdine(null, null, null);
            $nuovo_carrello = array();
            //cerco l'articolo in questione
            if($this->isCarrelloInizializzato() && !$this->isCarrelloEmpty()){
                $i=0;
                while($i < $this->getDimensioneCarrello()){
                    $temp_articolo_ordine = $_SESSION['fdv_carrello'][$i];
                    if($temp_articolo_ordine->getID_Tabella() == $id_tabella && $temp_articolo_ordine->getID_Prodotto() == $id_prodotto && $temp_articolo_ordine->getCassettaPersonalizzata() == 0){
                        //non faccio nulla
                    }
                    else{
                        array_push($nuovo_carrello, $temp_articolo_ordine);
                    }
                    $i++;
                }
                
                unset($_SESSION['fdv_carrello']);
                $_SESSION['fdv_carrello'] = array();
                $_SESSION['fdv_carrello'] = $nuovo_carrello;
                
                return true;
                
            }
            else{
                //carrello non inizializzato
                return -1;
            }            
        }
        
        public function rimuoviCassettaPersonalizzata($cp){
            //La funzione rimuove il contenuto di una cassetta personalizzata dal carrello
            $temp_articolo_ordine = new ArticoloOrdine(null, null, null);
            $nuovo_carrello = array();
            //cerco gli articoli in questione
            if($this->isCarrelloInizializzato() && !$this->isCarrelloEmpty()){
                $i=0;
                while($i < $this->getDimensioneCarrello()){
                    $temp_articolo_ordine = $_SESSION['fdv_carrello'][$i];
                    if($temp_articolo_ordine->getCassettaPersonalizzata() == $cp){
                        //non faccio nulla
                    }
                    else{
                        array_push($nuovo_carrello, $temp_articolo_ordine);
                    }
                    $i++;
                }
                unset($_SESSION['fdv_carrello']);
                $_SESSION['fdv_carrello'] = array();
                $_SESSION['fdv_carrello'] = $nuovo_carrello;
                
                return true;
            }
            else{
                //carrello non inizializzato
                return -1;
            }    
        }
        
        public function resetCP($cp){
            //La funzione si occupa di modificare tutti i valori di cp per eventuali modifiche
            $temp_articolo_ordine = new ArticoloOrdine(null, null, null);
            //devo settare i valori di cp aggiornandoli
            //se cp = x, devo diminuire di un valore di cp ad ogni elemento sucessivo a tale cp
            $i=0;
          
            while($i < $this->getDimensioneCarrello()){
                $temp_articolo_ordine = $_SESSION['fdv_carrello'][$i];
                if($temp_articolo_ordine->getCassettaPersonalizzata() > $cp){
                    $temp_articolo_ordine->setCassettaPersonalizzata($temp_articolo_ordine->getCassettaPersonalizzata() -1);
                }
                $i++;
            }
            return true;
        }
        
        public function setQuantitaArticolo($id_tabella, $id_prodotto, $qt){
            //La funzione cambia la quantità di un determinato articolo tramite valori passati alla funzione
            $temp_articolo_ordine = new ArticoloOrdine(null, null, null);
            if($this->isCarrelloInizializzato()){
                try{
                    $i=0;
                    while($i < count($_SESSION['fdv_carrello'])){
                        $temp_articolo_ordine = $_SESSION['fdv_carrello'][$i];
                        if($temp_articolo_ordine->getID_Tabella() == $id_tabella && $temp_articolo_ordine->getID_Prodotto() == $id_prodotto){
                            $temp_articolo_ordine->setQuantita($qt);
                            $_SESSION['fdv_carrello'][$i] = $temp_articolo_ordine;
                        }
                        $i++;
                    }
                    return true;
                }
                catch(Exception $e){
                    _e($e);
                    return false;
                }
            }
            else{
                //carrello non inizializzato
                return -1;
            }
        }
        
        public function svuotaCarrello(){
            //La funzione pulisce il carrello
            try{
                unset($_SESSION['fdv_carrello']);
                $_SESSION['fdv_carrello'] = array();
                return true;
            }
            catch(Exception $e){
                echo $e;
                return false;
            }
        }
        
        public function getDimensioneCarrello(){
            //La funzione restituisce la dimensione del carrello
            if($this->isCarrelloInizializzato() && !$this->isCarrelloEmpty()){
                return count($_SESSION['fdv_carrello']);
            }
            else{
                return false;
            }
        }
        
        public function getArticolo($count){
            //La funzione ritorna un oggetto Articolo_Ordine di una determinata posizione nel carrello
            if($this->isCarrelloInizializzato() && !$this->isCarrelloEmpty()){
                $temp_articolo_ordine = new ArticoloOrdine(null, null, null);
                $temp_articolo_ordine = $_SESSION['fdv_carrello'][$count];
                return $temp_articolo_ordine;
            }
            else{
                return false;
            }
        }
        
         public function getIdOrdine(){
            if(isset($_SESSION['id_ordine'])){
                return $_SESSION['id_ordine'];
            }
            else{
                return false;
            }                
        }
        
        public function rimuoviIdOrdine(){
            if(isset($_SESSION['id_ordine'])){
                try{
                    unset($_SESSION['id_ordine']);
                }
                catch(Exception $e){
                    _e($e);
                    return false;
                }
            }
            else{
                return false;
            }
        }
        
        public function salvaOrdineNelDatabase($id_cliente, $note){
            //salvo l'ordine nel database
            //istanzio le classi necessarie
            //lista_ordine individua l'identificativo dell'ultimo ordine settimanale in corso
            $lista_ordine = new ListaOrdine($this->wpdb);
            $v_lista_ordine = new ViewListaOrdine($lista_ordine);
                        
            if($this->isCarrelloInizializzato() && !$this->isCarrelloEmpty()){
                //carello inizializzato e non vuoto
                //inizio con salvare l'identificativo dell'ordine e poi aggiornerò il valore complessivo dell'ordine
                //ordine_cliente rappresenta la classe che popolarà la tabella corrispondente nel databse
                $ordine_cliente = new OrdineCliente($id_cliente, $v_lista_ordine->getIDUltimoOrdine());
                $ordine_cliente->setCostoTotale(0); //imposto a zero, poi lo aggiorno
                $ordine_cliente->setDataOrdine(date("Y-m-d H:i:s"));
                $ordine_cliente->setNote($note);
                $c_ordine_cliente = new ControllerOrdineCliente($ordine_cliente);
                $v_ordine_cliente = new ViewOrdineCliente($ordine_cliente);           
                
                if($c_ordine_cliente->saveOrdineCliente() != false){
                    //salvataggio avvenuto con successo
                    //salvo i valori degli articoli nel carrello
                    $totale_ordine = 0; //istanzio la variabile che determinerà il totale dell'ordine
                    $i=0;
                    $errors = 0;
                    while($i < count($_SESSION['fdv_carrello']) && $errors == 0){
                        //articolo_ordine è la classe che contiene i vari articoli ordinati
                        $temp_articolo_ordine = new ArticoloOrdine(null, null, null);
                        $temp_articolo_ordine = $_SESSION['fdv_carrello'][$i];
                        //faccio i conti per il gran totale
                        $totale_ordine += number_format((float)$temp_articolo_ordine->getPrezzoUnitario() * (float)$temp_articolo_ordine->getQuantita(),2);
                        //creo l'oggetto articolo_ordine 
                        $db_articolo_ordine = new ArticoloOrdine($temp_articolo_ordine->getID_Prodotto(), $temp_articolo_ordine->getID_Tabella(), $v_ordine_cliente->get_Last_ID_From_Cliente($id_cliente));
                        $db_articolo_ordine->setPrezzoUnitario($temp_articolo_ordine->getPrezzoUnitario());
                        $db_articolo_ordine->setQuantita($temp_articolo_ordine->getQuantita());
                        //lo salvo nel db
                        $c_articolo_ordine = new ControllerArticoloOrdine($db_articolo_ordine);
                        if(!$c_articolo_ordine->saveArticolo_Ordine()){
                            $errors++;
                        }                        
                        $i++;
                    }
                    if($errors == 0){
                        //se non ci sono stati errori, setto il gran totale
                        if(!$c_ordine_cliente->setCostoTotale($v_ordine_cliente->get_Last_ID_From_Cliente($id_cliente), $totale_ordine)){
                            $errors++;
                        }
                        else{
                            return true;
                        }
                    }
                    else{
                        //ci sono stati errori nel salvare Articolo_Ordine
                        return -3;
                    }
                }
                else{
                    //qualche errore nel salvare Ordine_cliente
                    return -2;
                }
                
            }
            else{
                //carrello non inizializzato e/o vuoto
                return -1;
            }
            
        }
    }

?>
