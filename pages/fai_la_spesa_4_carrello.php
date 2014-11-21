<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

             

if($carrello->isCarrelloInizializzato() && !$carrello->isCarrelloEmpty()){
    _e('<div id="visulizza-carrello">');
        _e('<span class="titolo-carrello">Nel Carrello</span><br><br>');
     //se il carrello è stato creato e non è vuoto, stampo il contenuto del carrello
    $i=0;
    $k=1;
    $scritto = false;
    //_e($carrello->getDimensioneCarrello().'<br>');
    //print_r($carrello->getDimensioneCarrello().'<br>');
    $totale_ordine = 0;

    while($i < $carrello->getDimensioneCarrello()){

        $temp_articolo_ordine = new ArticoloOrdine(null, null, null);
        $temp_articolo_ordine = $carrello->getArticolo($i);
        //_e($temp_articolo_ordine->getID_Prodotto());
        $tabella = $temp_articolo_ordine->getID_Tabella();
        if($temp_articolo_ordine->getCassettaPersonalizzata() == 0){
            //Cassetta normale o aggiunte alla cassetta
            if($tabella == 0){
                if($scritto == true){
                     _e('</div></div>');
                    $scritto = false;     
                }
                //Tabella Cassetta
                $cassetta = new Cassetta($wpdb);
                $v_cassetta = new ViewCassetta($cassetta);
                
                //DEVO FARE UNA CHIAMATA PER OTTENERE LE INFORMAZIONI DEL PRODOTTO CASSETTA
                $info_cassetta = $v_cassetta->getProdotto($temp_articolo_ordine->getID_Prodotto());
                //obj->Tipologia_Cassetta
                //obj->Prezzo
                //obj->ID_Foto                
                
                $foto_cassetta = $path.'cassetta-piena.jpg';
                if(image_exists($foto_cassetta)){ $foto_da_inserire = '<img src="'.$foto_cassetta.'">'; }
                else{ $foto_da_inserire = '<img src="'.$path.'non-disponibile.jpg" >'; }
                
                _e('<div class="visualizza-cassetta-preparata prodotto ');
                 if( ($i  % 2 == 0)){_e('pari"');} else{_e('dispari"');}
                _e('>');
                //_e( '<form style="margin:0; padding:0; border:0" action="'.$url.'tabella='.$temp_articolo_ordine->getID_Tabella().'&prodotto='.$temp_articolo_ordine->getID_Prodotto().'" method="post">');
                //_e(     '<input style="float:right;" type="submit" value="X" title="Rimuovi dal carrello" name="elimina-da-carrello">');
                _e(     $foto_da_inserire);
                _e(        '<span style="font-size:1em;">'.$temp_articolo_ordine->getQuantita().' x '.$info_cassetta->Tipologia_Cassetta.' - € '.$info_cassetta->Prezzo.'</span>');
                           
                //_e( '</form>');
                _e('</div>');
                $totale_ordine += (float)$temp_articolo_ordine->getQuantita() * (float)$info_cassetta->Prezzo;

            }
            else if($tabella == 1){
                //Tabella Frutta_Verdure
                if($scritto == true){
                     _e('</div></div>');
                    $scritto = false;     
                }
                $frutta_verdura = new Frutta_Verdura($wpdb);
                $v_frutta_verdura = new ViewFrutta_Verdura($frutta_verdura);
                
                //FACCIO LA CHIAMATA AL DB
                $fv_info = $v_frutta_verdura->getProdotto($temp_articolo_ordine->getID_Prodotto());
                                
                 //ottengo le coordinate dello sprite
                $coord = getCoordinateSprite($fv_info->ID_Foto);
                
                _e('<div class="visualizza-prodotto prodotto '); 
                if( ($i  % 2 == 0)){_e('pari"');} else{_e('dispari"');}
                _e('>');
                _e(     '<div class="fv-immagine-prodotto" style="background-position:-'.$coord['left'].'px -'.$coord['top'].'px; float:left"></div>');
                _e(     '<div class="descrizione">'.$temp_articolo_ordine->getQuantita().' '.$fv_info->Unita.' '.$fv_info->Nome_Prodotto.'<br>€ '.$fv_info->Prezzo.'</div>');
                _e('</div>');                                      
                $totale_ordine += (float)$temp_articolo_ordine->getQuantita() * (float)$fv_info->Prezzo;
            }
            else if($tabella == 2){
                //Tabella Aggiunta_Cassetta
                 if($scritto == true){
                     _e('</div></div>');
                    $scritto = false;     
                }
                $aggiunta_cassetta = new Aggiunta_Cassetta($wpdb);
                $v_aggiunta_cassetta = new ViewAggiunta_Cassetta($aggiunta_cassetta);
                
                //CHIAMATA A DB
                $ac_info = $v_aggiunta_cassetta->getProdotto($temp_articolo_ordine->getID_Prodotto());
                
                //ottengo le coordinate dello sprite
                $coord = getCoordinateSprite($ac_info->ID_Foto);
                
                _e('<div class="visualizza-prodotto prodotto ');   
                if( ($i  % 2 == 0)){_e('pari"');} else{_e('dispari"');}
                _e('>');
                _e(     '<div class="ac-immagine-prodotto" style="background-position:-'.$coord['left'].'px -'.$coord['top'].'px; float:left"></div>');
                _e(     '<div class="descrizione">'.$temp_articolo_ordine->getQuantita().' '.$ac_info->Unita.' '.$ac_info->Nome_Prodotto.'<br>€ '.$ac_info->Prezzo.'</div>');
                _e('</div>');        
                $totale_ordine += (float)$temp_articolo_ordine->getQuantita() * (float)$ac_info->Prezzo;
            }
        }
        else{
            //cassetta personalizzata            
            if($scritto == true && $k!= $temp_articolo_ordine->getCassettaPersonalizzata()){               
                 _e('</div></div>');
                $scritto = false;     
            }              

            if($k != $temp_articolo_ordine->getCassettaPersonalizzata() ){               
                $k++;               
            }

            if($scritto == false && $k == $temp_articolo_ordine->getCassettaPersonalizzata()){
                   _e('<div class="visualizza-cassetta-personalizzata prodotto ');
                   if( ($i  % 2 == 0)){_e('pari"');} else{_e('dispari"');}
                   _e('><strong>1 x Cassetta Personalizzata '.$k.'</strong>');
                   _e('<div style="margin-left:25px">');
                   $scritto = true;
                   $totale_cassetta = 0;
            }
            if($k == $temp_articolo_ordine->getCassettaPersonalizzata()){

                if($tabella == 1){
                    //Tabella Frutta_Verdura
                    $frutta_verdura = new Frutta_Verdura($wpdb);
                    $v_frutta_verdura = new ViewFrutta_Verdura($frutta_verdura);
                    $fv_info = $v_frutta_verdura->getProdotto($temp_articolo_ordine->getID_Prodotto());
                    _e($temp_articolo_ordine->getQuantita().' '.$fv_info->Unita);
                    _e(' '.$fv_info->Nome_Prodotto);
                    _e(' - € '.$fv_info->Prezzo);
                    _e('<br>');
                    //$totale_cassetta += (float)$temp_articolo_ordine->getQuantita() * (float)$v_frutta_verdura->getPrezzo($temp_articolo_ordine->getID_Prodotto());
                    $totale_ordine += (float)$temp_articolo_ordine->getQuantita() * (float)$fv_info->Prezzo;
                }
                else if($tabella == 2){
                    //Tabella Aggiunta_Cassetta
                    $aggiunta_cassetta = new Aggiunta_Cassetta($wpdb);
                    $v_aggiunta_cassetta = new ViewAggiunta_Cassetta($aggiunta_cassetta);
                    $ac_info = $v_aggiunta_cassetta->getProdotto($temp_articolo_ordine->getID_Prodotto());
                    _e($temp_articolo_ordine->getQuantita().' '.$ac_info->Unita);
                    _e(' '.$ac_info->Nome_Prodotto);
                    _e(' - € '.$ac_info->Prezzo);
                    _e('<br>');
                    //$totale_cassetta += (float)$temp_articolo_ordine->getQuantita() * (float)$v_frutta_verdura->getPrezzo($temp_articolo_ordine->getID_Prodotto());
                    $totale_ordine += (float)$temp_articolo_ordine->getQuantita() * (float)$ac_info->Prezzo;
                }
            }
        }
       
        $i++;
    }
    if($scritto == true){
        _e('</div></div>');
        $scritto = false;     
    }
    
    $cliente = new Cliente(null, null);
    $v_cliente = new ViewCliente($cliente);
    
    //Lo sconto vale solo per gli utenti registrati
    $current_user = wp_get_current_user();        
    $sconto = $v_cliente->getSconto($current_user->user_email);
   
    if($sconto != null && $sconto != ''){
        //ho lo sconto
    }
    else{
        //non ho lo sconto
        $sconto = 0;
    }
   
    
    //controllo il valore dell'ordine e le eventuali scontistiche
    _e('<div id="totale-ordine">');
    
    if((float)$totale_ordine < 12){
        //ordine non effettuabile
        _e('Totale carrello: <span style="font-weight:bold; color:red">€ '. number_format((float)$totale_ordine,2).'</span>');
    }
    else if((float)$totale_ordine >= 12 && (float)$totale_ordine < 18){
        //ordine + spese di spedizione
        $temp_totale_ordine = $totale_ordine+6;
        _e('Totale carrello: <strong>€ '.number_format($totale_ordine,2).'</strong> + Spese di spedizione: <strong>€ 6.00</strong> =  <strong>€ '. number_format((float)($temp_totale_ordine),2).'</strong>');
        if($sconto != 0){
            $prezzo_da_scontare = ((float)$temp_totale_ordine * (float)$sconto) / 100;
            $totale_scontato = (float)$temp_totale_ordine - (float)$prezzo_da_scontare;
            if((float)$totale_scontato < 18){
                $totale_scontato = 18;
            }
            _e('<br>Sconto: '.$sconto.'%');
            _e('<br>Totale ordine: <strong>€ '.number_format((float)$totale_scontato, 2).'</strong>');
            
            $temp_totale_ordine = $totale_scontato;
        }
        
        $totale_ordine = $temp_totale_ordine;
    }    
    else{
       //ordine senza spese di spedizione
        $temp_totale_ordine = $totale_ordine;
       _e('Totale carrello: <strong>€ '.number_format($totale_ordine,2).'</strong> + Spese di spedizione: <strong>€ 0.00</strong> =  <strong>€ '. number_format((float)($temp_totale_ordine),2).'</strong>');
       if($sconto != 0){
            $prezzo_da_scontare = ((float)$temp_totale_ordine * (float)$sconto) / 100;
            $totale_scontato = (float)$temp_totale_ordine - (float)$prezzo_da_scontare;
            if((float)$totale_scontato < 18){
                $totale_scontato = 18;
            }
            _e('<br>Sconto: '.$sconto.'%');
            _e('<br>Totale ordine: <strong>€ '.number_format((float)$totale_scontato, 2).'</strong>');   
            $temp_totale_ordine = $totale_scontato;
        }
        $totale_ordine = $temp_totale_ordine;
    }       
    
    _e('</div>');
    _e('</div>');
    
    
 }


?>