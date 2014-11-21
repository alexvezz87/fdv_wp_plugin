<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/
    
if(isset($_POST['aggiungi-nuovo-ordine'])){
    global $wpdb;
    
    //La pagina si occupa di salvare nel database gli articoli contenuti nel carrello e di creare il cliente
     //creo il carrello
    $carrello = new Carrello();    
    $riepilogo = array();
    
    if(isset($_SESSION['fdv_riepilogo'])){      
        $riepilogo = $_SESSION['fdv_riepilogo'];      
    }
    else{
       _e('some troubles :(');
    }
    
    
    if($carrello->isCarrelloInizializzato() && !$carrello->isCarrelloEmpty()){
       
    }
    
    //var_dump($_SESSION['fdv_riepilogo']);   
    //Creo il cliente
   
    $cliente = new Cliente(null, $riepilogo['email']);
    $c_cliente = new ControllerCliente($cliente);
    $v_cliente = new ViewCliente($cliente);
    
    $contatti = new Contatti($riepilogo['email']);
    $c_contatti = new ControllerContatti($contatti);
    
    //controllo se il cliente esiste già
    
    $errors = 0;
    if($carrello->isCarrelloInizializzato() && !$carrello->isCarrelloEmpty()){
        
        $id_cliente = $v_cliente->isCliente($riepilogo['email']);
        //print_r('id_cliente: '.$id_cliente);
        if($id_cliente != null && $id_cliente != ''){        
            //il cliente esiste già, aggiorno i dati in mio possesso
            $c_cliente->setNominativo($riepilogo['email'], $riepilogo['nominativo']);
            //aggiorno anche i dati relativi ai contatti
            $c_contatti->setCellulare($riepilogo['email'], $riepilogo['cellulare']);
            $c_contatti->setTelefono($riepilogo['email'], $riepilogo['telefono']);        
            $c_contatti->setIndirizzoSpedizione($riepilogo['email'], $riepilogo['indirizzo']);

        }
        else{
            //_e('<br>dentro');
            //il cliente non esiste, lo inserisco nel database
            $cliente->setDatiPrivato($riepilogo['nominativo'], '');        
            $contatti->setIndirizzoSpedizione($riepilogo['indirizzo']);
            $contatti->setTelefono($riepilogo['telefono']);
            $contatti->setCellulare($riepilogo['cellulare']);
            //salvo i dati dei contatti nel database
            if($c_cliente->saveCliente()){
                if(!$c_contatti->saveContatti()){
                    $errors++;
                } 
            }
            else{
                $errors++;
            }

        }

        
        //se non ci sono errori, salvo il carrello come ordine
        $lista_ordine = new ListaOrdine($wpdb);
        $v_lista_ordine = new ViewListaOrdine($lista_ordine);

        //creo ordine cliente con l'identificativo del cliente uguale alla mail del cliente, e l'identificativo dell'ordine, quello dell'ultima sessione aperta
        $ordine_cliente = new OrdineCliente($v_cliente->isCliente($riepilogo['email']), $v_lista_ordine->getIDUltimoOrdine());
        $ordine_cliente->setDataOrdine(date("Y-m-d H:i:s"));
        $ordine_cliente->setNote($riepilogo['note']);
        $ordine_cliente->setCostoTotale(0); //provvisorio

        //salvo
        $c_ordine_cliente = new ControllerOrdineCliente($ordine_cliente);
        $c_ordine_cliente->saveOrdineCliente();

        $v_ordine_cliente = new ViewOrdineCliente($ordine_cliente);


        //ora salvo anche i diversi prodotti che compongono l'ordine
        $i=0;
        $costo_totale = 0;
        //_e(count($_SESSION['fdv_carrello']));
        while($i < $carrello->getDimensioneCarrello()){
            $articolo_ordine = new ArticoloOrdine(null, null, null);
            $articolo_ordine = $carrello->getArticolo($i);
            if($articolo_ordine->getID_Prodotto() != 0){
                //SETTO IL NOME DEL PRODOTTO e L'UNITA DI MISURA --> MODIFICA AD ERRORE DI SCAMBIO ARTICOLI CON XLS DIVERSI
                //individuo la tabella
                if($articolo_ordine->getID_Tabella() == 0){
                    //cassetta
                    //istanzio un oggetto view Cassetta
                    $c = new Cassetta($wpdb);
                    $v_cassetta = new ViewCassetta($c);
                    $articolo_ordine->setNomeArticolo($v_cassetta->getNome($articolo_ordine->getID_Prodotto()));
                    $articolo_ordine->setUnitaMisura(""); //le cassette non hanno unità di misura
                }
                else if($articolo_ordine->getID_Tabella() == 1){
                    //Frutta e Verdura
                    //istanzio un oggetto Frutta Verdura
                    $fv = new Frutta_Verdura($wpdb);
                    $v_fv = new ViewFrutta_Verdura($fv);
                    $articolo_ordine->setNomeArticolo($v_fv->getNome($articolo_ordine->getID_Prodotto()));
                    $articolo_ordine->setUnitaMisura($v_fv->getUnita($articolo_ordine->getID_Prodotto()));
                }
                else if($articolo_ordine->getID_Tabella() == 2){
                    //Aggiunta Cassetta
                    //istanzio un oggetto Aggiunta Cassetta
                    $ag = new Aggiunta_Cassetta($wpdb);
                    $v_ag = new ViewAggiunta_Cassetta($ag);
                    $articolo_ordine->setNomeArticolo($v_ag->getNome($articolo_ordine->getID_Prodotto()));
                    $articolo_ordine->setUnitaMisura($v_ag->getUnita($articolo_ordine->getID_Prodotto()));
                }
                else{
                    $articolo_ordine->setNomeArticolo("Articolo sconosciuto");
                    $articolo_ordine->setUnitaMisura("Misura sconosciuta");
                }
                //FINE SET NOME PRODOTTO                

                //_e('<br>id_last_ordine: '.$v_ordine_cliente->get_Last_ID_From_Cliente($v_cliente->isCliente($cliente->getEmail())));
                $articolo_ordine->setID_Ordine($v_ordine_cliente->get_Last_ID_From_Cliente($v_cliente->isCliente($cliente->getEmail())));
                //_e('<br>id_ordine'.$articolo_ordine->getID_Ordine());
                $costo_totale += (float)$articolo_ordine->getQuantita() * (float)$articolo_ordine->getPrezzoUnitario();
                //salvo l'articolo nel db
                $c_articolo_ordine = new ControllerArticoloOrdine($articolo_ordine);
                if(!$c_articolo_ordine->saveArticolo_Ordine())
                    $errors++;
            }
            $i++;
        }

        //ho salvato tutti gli articoli
        //Aggiorno il valore complessivo dell'ordine
        $c_ordine_cliente->setCostoTotale($v_ordine_cliente->get_Last_ID_From_Cliente($v_cliente->isCliente($cliente->getEmail())), $costo_totale);
        $c_ordine_cliente->setCostoFinale($v_ordine_cliente->get_Last_ID_From_Cliente($v_cliente->isCliente($cliente->getEmail())), $riepilogo['costo_finale']);              




        //se non ho errori, invio le mail al cliente per riepilogo e all'amministratore per accettazione

        //ottengo un array contenente i valori che mi interessano di un determinato ID_Ordine
        $temp_articolo_ordine = new ArticoloOrdine(null, null, null);
        $v_articolo_ordine = new ViewArticoloOrdine($temp_articolo_ordine);
        //prendo tutti gli id degli articoli di un determinato ordine
        $ids = $v_articolo_ordine->get_IDs_From_Ordine($v_ordine_cliente->get_Last_ID_From_Cliente($v_cliente->isCliente($cliente->getEmail())));

        //ora che conosco gli identificativi dei prodotti di questo ordine, devo suddividerli a seconda delle cassette personalizzate o meno
        $cps = array(); //array di cassette personalizzate
        $cp = array(); //array di cassetta personalizzata
        $no_cp = array(); //array del resto dei prodotti

        $i=0;
        $current_cp = 0;

        while($i < count($ids)){
            $temp_articolo_ordine = $v_articolo_ordine->getArticolo_Ordine($ids[$i]);



            if($temp_articolo_ordine->getCassettaPersonalizzata() > 0){
                //lavoro sulle cassette personalizzate                  

                if($current_cp < $temp_articolo_ordine->getCassettaPersonalizzata()){
                    //se ho cassette personalizzate allora la prima avrà valore sicuramente = 1
                    if($current_cp > 0){
                        //se non è la prima transazione, carico il valore di cp nell'array cps
                        array_push($cps, $cp);
                        //azzero l'array delle singole cassette
                        unset($cp);
                        $cp = array();
                    }
                    $current_cp++;
                }
                if($temp_articolo_ordine->getCassettaPersonalizzata() == $current_cp){
                    //lavoro sulla cassetta personalizzata che corrisponde al valore attuale
                    //metto 
                    array_push($cp, $temp_articolo_ordine);
                }

            }
            else{
                array_push($no_cp, $temp_articolo_ordine);
            }

            if($i == count($ids) - 1){
                 if(count($cp) > 0){
                     array_push($cps, $cp);
                 }
            }     

            $i++;
        }
        
        
//        //flag di invio email avvenuto
//        $sent_mail = false;
//        $invio_amministratore = false;
//        $sent_notifica_debug = false;            
//        $sent_notifica_admin = false;
//        $sent_notifica_cliente = false;    
//
//        //contatori 
//        $counter_sent_mail = 0;
//        $counter_invio_amministratore = 0;            
//        $counter_notifica_admin = 0;
//        $counter_notifica_cliente = 0;
//        $counter_debug = 0;
//
//        //INVIO UNA MAIL DI NOTIFICA SENZA FORMATTAZIONE HTML AD ENTRAMBI GLI INDIRIZZI (AMMINISTRATORE E UTENTE)
//        include 'invio_mail_notifica.php';
//
//        //MAIL PER AMMINISTRATORE
//        include 'invio_mail_amministratore.php';
//
//        $invio_cliente = false;
//        //INVIO LA MAIL DI ORDINE RICEVUTO AL CLIENTE
//        include 'invio_mail_cliente.php';
//
//        //INVIO MAIL DEBUG AD ALEXSOLUZIONIWEB
//        include 'invio_mail_debug';    
        
        
        //metto dentro a riepilogo i prodotti inseriti
        $riepilogo['cps'] = $cps;
        $riepilogo['no_cp'] = $no_cp;
        
        
        //INVIO LE MAIL:
        //1. Invio a info@fruttidafavola.it
        $invio_a_fdv = inviaMail('info@alexsoluzioniweb.it', $riepilogo, true);
        
        //2. Invio al cliente
        $invio_cliente = inviaMail($riepilogo['email'], $riepilogo, true);
        
        //3. Invio debug ad admin
        $invio_debug = inviaMail('alexvezz87@gmail.com', $riepilogo, false);
         

        //devo distruggere il carrello e le variabili di sessione attive
        _e('<div id="ordine-registrato-ok">Ordine registrato correttamente.');
            if($invio_cliente == true){
                _e('<br>Abbiamo inviato una mail di riepilogo al suo indrizzo di posta elettronica.');
            }
            else{
                _e("<br>Ci sono stati problemi di invio mail, ma il suo ordine è stato registrato correttamente.<br>A breve riceverà una mail di conferma.");
                $title_mail_2 = "Ordine ricevuto da ".$riepilogo['nominativo'];	
                $msg_2 = "E' stato ricevuto un ordine da ".$riepilogo['nominativo']." che ha riscontrato problematiche nell'invio della mail di conferma. Controllare il riepilogo ordini su fruttidafavola.it e ricontattare il cliente per l'avvenuto ordine.";
                wp_mail('info@fruttidafavola.it', $title_mail_2, $msg_2);
            }

            sleep(1);
              $carrello->svuotaCarrello();

        if(isset($_SESSION['count_personalizzate'])){
            unset($_SESSION['count_personalizzate']);

        }
//        if(isset($_SESSION['fdv_riepilogo'])){
//            unset($_SESSION['fdv_riepilogo']);
//
//        }

        _e('</div>');           
        
    }
    else{
        _e('carrello vuoto...');
    }
    
}
else{
    //non faccio nulla
}

?>