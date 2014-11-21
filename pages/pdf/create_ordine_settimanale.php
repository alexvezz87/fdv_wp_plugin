<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

    $pdf = new MyFPDF();
    //creo il pdf
    $pdf->Open();
    $pdf->AddPage();
    $pdf->SetTextColor(0); // Con queste due funzioni imposto il carattere


    //compongo il pdf
    $pdf->SetFont('Arial','',14);
    $titolo = "Ordine Settimanale - Periodo $giorno_apertura - $giorno_chiusura";
    $pdf->MultiCell(500, 10, $titolo, 0, 'center');


    //devo ottenere i valori che mi interessano di tutti gli ordini di un determinato periodo
    $ordine_cliente = new OrdineCliente(null, null);
    $v_ordine_cliente = new ViewOrdineCliente($ordine_cliente);
    //prendo tutti gli ordini di questo periodo
    $ids_ordine = $v_ordine_cliente->get_IDs_From_Ordine($periodo[$i]);
    $rows = array();
    //scorro la lista per sapere i prodotti presenti in questo ordine
    
    //MODIFICA
    //devo fare un totale di tutti i prodotti ordinati e sommare ogni quantita di ciascun prodotto per ciascun ordine
    
    //creo un array che contenga un identificativo del prodotto e la quantita richiesta che andrà ad aumentare i valori corrispondenti molteplici
    $prodotti = array();
    
    
    if(count($ids_ordine)>0){
        //ho degli ordini
        $count_ordine = 0;
        $prodotti = array();
        while($count_ordine < count($ids_ordine)){
            //devo scorrere gli articoli negli ordini e stampare a video le diverse quantità richieste per ordine
            $articolo_ordine = new ArticoloOrdine(null, null, null);
            $v_articolo_ordine = new ViewArticoloOrdine($articolo_ordine);
            //trovo gli articoli che compongono i diversi ordini
            $ids_articoli = $v_articolo_ordine->get_IDs_From_Ordine($ids_ordine[$count_ordine]);
            if(count($ids_articoli > 0)){

                //ho degli articoli che compongono l'ordine in questione
                $count_articolo = 0;
                
                while($count_articolo < count($ids_articoli)){
                    $row = array();
                    //row sarà composta in questo modo
                    //row[0] = id_ordine
                    //row[1] = quantita
                    //row[2] = nome prodotto
                    //row[3] = cassetta personalizzata
                    
                    //salvo l'articolo nella variabile
                    $articolo_ordine = $v_articolo_ordine->getArticolo_Ordine($ids_articoli[$count_articolo]);
                    //faccio le distinzioni
                   
                    
                    $prodotto = array();
                    //$prodotto[0] --> ID_PRODOTTO
                    //$prodotto[1] --> ID_TABELLA
                    //$prodotto[2] --> QUANTITA'
                    $prodotto['id_prodotto'] = $articolo_ordine->getID_Prodotto();
                    $prodotto['id_tabella'] = $articolo_ordine->getID_Tabella();
                    $prodotto['qt'] = $articolo_ordine->getQuantita();
                    
                    if(count($prodotti) ==  0){
                        //il vettore di prodotto è vuoto, inserisco il primo elemento
                        //inserisco il valore di prodotto nell'array di prodotti
                        array_push($prodotti, $prodotto);
                    }
                    else{
                        //altrimenti scorro il vettore alla ricerca di un prodotto che potrebbe fare match
                        $count_prodotto = 0;
                        $trovato = false;
                        while($count_prodotto < count($prodotti) && $trovato == false){
                            //_e('id_prod: '.$prodotti[$count_prodotto]['id_prodotto']);
                            //rimango dentro al vettore finchè non trovo un corrispondente (dopo averlo aggiornato) oppure finchè non lo scorro tutto
                                                        
                            if($prodotti[$count_prodotto]['id_prodotto'] == $prodotto['id_prodotto'] && $prodotti[$count_prodotto]['id_tabella'] == $prodotto['id_tabella']){
                                //ho il riscontro
                                
                                $prodotti[$count_prodotto]['qt'] += $prodotto['qt'];
                                $trovato = true;
                            }                            
                            $count_prodotto++;
                        }
                        if($trovato == false){
                            //se non ho trovato alcun riscontro, vuol dire che il prodtto in questione è nuovo
                            //lo inserisco nell'array
                            array_push($prodotti, $prodotto);
                        }
                        
                        
                    }
                    
                    
                    

                    $count_articolo++;
                }
                //FINE CICLO DI CATALOGAZIONE ARTICOLI

            }

            $count_ordine++;
        }
        //FINE-CICLO DEGLI ARTICOLI
        
        //inizio il ciclo per impostare le tabelle di stampa del pdf
        
        $count_prodotti = 0;
        while($count_prodotti < count($prodotti)){
            
            //scorro il vettore controllando i vari campi
                        
            if($prodotti[$count_prodotti]['id_tabella'] == 0){
                        //cassetta
                        $cassetta = new Cassetta($wpdb);
                        $v_cassetta = new ViewCassetta($cassetta);
                        
                        $row[0] = $prodotti[$count_prodotti]['qt'];
                        $row[1] = $v_cassetta->getTipologiaCassetta($prodotti[$count_prodotti]['id_prodotto']);
                        
                    }
                    else if($prodotti[$count_prodotti]['id_tabella']==1){
                        //Frutta_verdura
                        $fv = new Frutta_Verdura($wpdb);
                        $v_fv = new ViewFrutta_Verdura($fv);
                       
                        $row[0] = $prodotti[$count_prodotti]['qt'].' '.$v_fv->getUnita($prodotti[$count_prodotti]['id_prodotto']);
                        $row[1] = $v_fv->getNomeProdotto($prodotti[$count_prodotti]['id_prodotto']);                        
                    }
                    else if($prodotti[$count_prodotti]['id_tabella']==2){
                        //Aggiunta_cassetta
                        $ac = new Aggiunta_Cassetta($wpdb);
                        $v_ac = new ViewAggiunta_Cassetta($ac);

                        $row[0] = $prodotti[$count_prodotti]['qt'].' '.$v_ac->getUnita($prodotti[$count_prodotti]['id_prodotto']);
                        $row[1] = $v_ac->getNomeProdotto($prodotti[$count_prodotti]['id_prodotto']);                        
                    }
                   

                    array_push($rows, $row);
            
            
            $count_prodotti++;
        }
        
        

    }
    else{
        $pdf->SetFont('Arial','',8);
        $pdf->MultiCell(100, 10, 'Non ci sono ordini presenti', 0, 'center');
    }

         $header = array('Qt', 'Nome');
         $pdf->SetFont('Arial','',8);
         $pdf->TwoColumnsTable($header, $rows);

    //salvo il pdf
    $file_pdf = 'fdv_ordine-settimanale-'.$periodo[$i].'.pdf';
    $pdf->Output($file_pdf, 'F');
    $path = admin_url();
    _e('<a target="_blank" href="'.$file_pdf.'">'.$file_pdf.'</a>');
    
    //print_r($prodotti);

?>