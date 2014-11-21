<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

    //La pagina contiene altri prodotti che possono essere aggiunti all'ordine
    global $wpdb;  
    $path = plugins_url().'/gestione_ordine/images/prodotti/';
    
    //visualizziamo il carrello
    
    //CREO IL CARRELLO
    $carrello = new Carrello();
    
    $url = curPageURL();
    $temp_url = explode('&cassetta', $url);
    if($temp_url != false && count($temp_url)>0){
        $url = $temp_url[0];
    }
    $temp_url = explode('&tabella', $url);
    if($temp_url != false && count($temp_url)>0){
        $url = $temp_url[0];
    }
    if(strpos($url, '?')!=false){
        $url .= "&";
    }
    else{
        $url .= "?";
    }
    
    //Ascoltatore aggiunte a carrello
    if(isset($_POST['aggiungi-prodotto'])){
        $id_tabella = strip_tags($_GET['tabella']);
        $id_prodotto = strip_tags($_GET['prodotto']);
        $qt = strip_tags((isset($_POST['quantita'])) ? trim($_POST['quantita']) : '');
        //devo aggiungere il prodotto al carrello
        //l'articolo è identificato da tre valori, identificati dell'articolo, la tabella di appartenenza (Casette, Frutta_Verdura, Aggiunta_Cassetta) 
         //e un identificativo univoco riferito alla sessione che mi indica l'ordine in questione
         
        $articolo_ordine = new ArticoloOrdine(session_id(), $id_tabella, $id_prodotto);         
        $articolo_ordine->setQuantita($qt);
        $articolo_ordine->setCassettaPersonalizzata(0); //non è cassetta personalizzata
        
        if($id_tabella == 1){
            //Frutta_Verdura
            $frutta_verdura = new Frutta_Verdura($wpdb);
            $v_frutta_verdura = new ViewFrutta_Verdura($frutta_verdura);
            $articolo_ordine->setPrezzoUnitario($v_frutta_verdura->getPrezzo($id_prodotto)); //imposto il prezzo
        }
        else if($id_tabella == 2){
            //Aggiunta_Cassetta
            $aggiunta_cassetta = new Aggiunta_Cassetta($wpdb);
            $v_aggiunta_cassetta = new ViewAggiunta_Cassetta($aggiunta_cassetta);
            $articolo_ordine->setPrezzoUnitario($v_aggiunta_cassetta->getPrezzo($id_prodotto)); //imposto il prezzo
        }
        
        
         
         //ho creato l'elemento, ora lo salvo nel carrello
         if($carrello->isCarrelloInizializzato()){
             //controllo se il carrello esiste             
             $carrello->aggiungiArticolo($articolo_ordine);
         }
    }
     
//MOSTRA LA GUIDA ALGLI ACQUISTI
    _e('<div id="contenitore-guida">');
    _e('    <div id="step1" class="step active">');
    _e('     <h3>1</h3> <h4><a href="http://alexsoluzioniweb.it/progetti/fdv/fai-la-spesa/step-1/">SCEGLI LA CASSETTTA</a></h4>');
    _e('     <p>Aggiungi all\'ordine una cassetta già fatta oppure componila con i prodotti che vuoi.</p>');
    _e('    </div>');
    _e('    <div class="middle-step"></div>');
    _e('    <div id="step2" class="step active">');
    _e('     <h3>2</h3><h4>AGGIUNGI PRODOTTI</h4>');
    _e('     <p>Puoi aggiungere ulteriori prodotti alla tua cassetta.</p>');
    _e('    </div>');
    _e('    <div class="middle-step"></div>');
    _e('    <div id="step3" class="step">');
    _e('     <h3>3</h3><h4>DATI DI SPEDIZIONE</h4>');
    _e('     <p>Inserisci i tuoi dati, oppure accedi se sei già un nostro cliente.</p>');
    _e('    </div>');
    _e('    <div class="middle-step"></div>');
     _e('    <div id="step4" class="step">');
    _e('     <h3>4</h3> <h4>CONFERMA ORDINE</h4>');
    _e('     <p>L\'ordine effettuato ti soddisfa? Inviacelo!</p>');
    _e('    </div>');
    _e('</div>');
    _e('<div style="clear:both; display:block"></div>');   
        
    //MOSTRA IL CARRELLO    
    if($carrello->isCarrelloInizializzato() && !$carrello->isCarrelloEmpty()){
        include 'fai_la_spesa_2_carrello.php';        
    }
    
    //ora mostro le aggiunte che si possono effettuare
    _e('<h2>Vuoi aggiungere altro ?</h2>');
    
    
   _e('<h3 style="text-align:center">Frutta e Verdura</h3>');
   _e('<div id="frutta-verdura">');
   
   $fv = new Frutta_Verdura($wpdb);
   $v_fv = new ViewFrutta_Verdura($fv);
   
   //CHIAMATA A DB CHE OTTIENE TUTTI I PRODOTTI CONTENUTI NELLA TABELLA FRUTTA_VERDURA
   $fv_prodotti = $v_fv->getProdottiVetrina();
   //ritorna un array di obj così costituito
   //obj->ID
   //obj->Tipologia_Prodotto
   //obj->Nome_Prodotto
   //obj->Prezzo
   //obj->Unita
   //obj->ID_Foto
   
   //ottengo un vettore di Tipologie_Prodotto su come suddividere dopo i prodotti
   if(count($fv_prodotti)>0){
        //ho dei prodotti
        $fv_types = array();
        $fv_count = 0;
        $temp_type="";
        while($fv_count < count($fv_prodotti)){
            if($temp_type != $fv_prodotti[$fv_count]->Tipologia_Prodotto){
                $temp_type = $fv_prodotti[$fv_count]->Tipologia_Prodotto;
                array_push($fv_types, $temp_type);
            }                        
            $fv_count++;
        }
        
        if(count($fv_types) > 0){
            //ho dei tipi di prodotto            
            $fv_types_count=0;
            while($fv_types_count < count($fv_types)){
                _e('<div class="tipologia-frutta-verdura">');
                _e('   <h4>'.$fv_types[$fv_types_count].'</h4>');
                $fv_count = 0;
                while($fv_count < count($fv_prodotti)){
                    if($fv_prodotti[$fv_count]->Tipologia_Prodotto == $fv_types[$fv_types_count]){
                        //ho un riscontro tra la tipologia presente ed il prodotto
                        
                        _e('<div id="prod-1-'.$fv_prodotti[$fv_count]->ID.'" class="prodotto ');
                        if($fv_count % 2 == 0){ _e(' pari">'); }else{ _e(' dispari">');}
                        _e(' <form action="'.$url.'tabella=1&prodotto='.$fv_prodotti[$fv_count]->ID.'" method="post">');
                        _e('    <table cellpadding="3" >');
                        
                                               
                         //ottengo le coordinate dell'immagine relativa nello sprite
                         $coord = getCoordinateSprite($fv_prodotti[$fv_count]->ID_Foto);       
                        
                        //scrivo la riga
                        _e('        <tr>');
                        
                        _e('            <td rowspan="2" class="td-immagine"><div class="fv-immagine-prodotto" style="background-position:-'.$coord['left'].'px -'.$coord['top'].'px"></div></td>'
                         . '            <td colspan="3" class="td-titolo">'.$fv_prodotti[$fv_count]->Nome_Prodotto.'</td>'
                         . '        </tr>');
                         _e('       <tr valign="bottom">');
                        
                        _e('            <td><strong>€ '.$fv_prodotti[$fv_count]->Prezzo.'</strong> <span style="font-size:0.8em">a '.$fv_prodotti[$fv_count]->Unita.'</span></td>'
                          .'            <td><input class="qt-prodotto" type="text" value="1" name="quantita"></td>'
                          . '        <td ><input title="Aggiungi al Carrello" class="add-button" type="submit" name="aggiungi-prodotto" value=""></td></tr>');
                        _e('    </table>');
                        _e('   </form>');
                        _e('</div>');
                    }             
                                    
                 $fv_count++;   
                }
                _e('    <div style="min-height:1px; width:100%; float:none; clear:both; display:block; padding:0; margin:0" ></div>');
                _e('</div>'); //fine div->class:tipologia-frutta-verdura   
                $fv_types_count++;
            }
        }
        
   }
   else{
       //non ho dei prodotti
       _e('Prodotti Frutta e Verdura non disponibili');
   }
   _e('</div>'); //fine div->id:frutta-verdura
   
   _e('<h3 style="text-align:center">Altri Prodotti</h3>');
   _e('<div id="altre-aggiunte">');
   
   $ac = new Aggiunta_Cassetta($wpdb);
   $v_ac = new ViewAggiunta_Cassetta($ac);
   
   //CHIAMATA A DB PER OTTENERE TUTTI I PRODOTTI CONTENUTI NELLA TABELLA AGGIUNTA CASSETTA
   $ac_prodotti = $v_ac->getProdottiVetrina();
   //viene restituito un array di object così strutturato
   //obj->ID
   //obj->Tipologia_Prodotto
   //obj->Nome_Prodotto
   //obj->Prezzo
   //obj->Unita
   //obj->Peso
   //obj->Note
   //obj->ID_Foto
   
    //ottengo un vettore di Tipologie_Prodotto su come suddividere dopo i prodotti
   if(count($ac_prodotti)>0){
        //ho dei prodotti
        $ac_types = array();
        $ac_count = 0;
        $temp_type="";
        while($ac_count < count($ac_prodotti)){
            if($temp_type != $ac_prodotti[$ac_count]->Tipologia_Prodotto){
                $temp_type = $ac_prodotti[$ac_count]->Tipologia_Prodotto;
                array_push($ac_types, $temp_type);
            }                        
            $ac_count++;
        }
        
        if(count($ac_types) > 0){
            //ho dei tipi di prodotto            
            $ac_types_count=0;
            while($ac_types_count < count($ac_types)){
                _e('<div class="tipologia-aggiunta-cassetta">');
                _e('   <h4>'.$ac_types[$ac_types_count].'</h4>');
                $ac_count = 0;
                while($ac_count < count($ac_prodotti)){
                    if($ac_prodotti[$ac_count]->Tipologia_Prodotto == $ac_types[$ac_types_count]){
                        //ho un riscontro tra la tipologia presente ed il prodotto
                        _e('<div class="prodotto ');
                        if($ac_count % 2 == 0){ _e('pari">');} else {_e('dispari">');}
                        _e(' <form action="'.$url.'tabella=2&prodotto='.$ac_prodotti[$ac_count]->ID.'" method="post">');
                        _e('    <table cellpadding="3" >');
                        
                        //ottengo le coordinate dell'immagine relativa nello sprite
                        $coord = getCoordinateSprite($ac_prodotti[$ac_count]->ID_Foto);                
                        
                        //scrivo la riga
                        _e('        <tr>');
                        //if($ac_count % 2 == 0){ _e(' style="background:rgba(167,205,51,0.5)">'); }
                        //else{ _e('>'); }
                        _e('            <td rowspan="2" class="td-immagine"><div class="ac-immagine-prodotto" style="background-position:-'.$coord['left'].'px -'.$coord['top'].'px"></div></td>'
                         . '            <td colspan="3" class="td-titolo">'.$ac_prodotti[$ac_count]->Nome_Prodotto.'</td>'
                        
                         . '            '
                         . '        </tr>');
                         _e('       <tr valign="bottom">');
                        //if($ac_count % 2 == 0){ _e(' style="background:rgba(167,205,51,0.5)">'); }
                        //else{ _e('>'); }
                        _e('            <td><strong>€ '.$ac_prodotti[$ac_count]->Prezzo.'</strong> <span style="font-size:0.8em">a '.$ac_prodotti[$ac_count]->Unita.'</span></td>'
                          .'            <td><input class="qt-prodotto" type="text" value="1" name="quantita"></td>'
                          . '       <td rowspan="2" ><input title="Aggiungi al Carrello" class="add-button" type="submit" name="aggiungi-prodotto" value=""></td></tr>');
                        _e('    </table>');
                        _e('   </form>');
                        _e('</div>');
                    }             
                                    
                 $ac_count++;   
                }
                 _e('    <div style="min-height:1px; width:100%; float:none; clear:both; display:block; padding:0; margin:0" ></div>');
                _e('</div>'); //fine div->class:tipologia-aggiunta-cassetta 
                $ac_types_count++;
            }
        }
       
        
   }
   else{
       //non ho dei prodotti
       _e('Altri prodotti non disponibili');
   }
        
   
   _e('</div>'); //fine div->id:altre-aggiunte
    
   
?>