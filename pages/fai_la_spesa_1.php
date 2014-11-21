<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/
       
     global $wpdb;  
     $path = plugins_url().'/gestione_ordine/images/prodotti/';
     
    //La pagina si occupa di mostrare il listino di vendita di frutti da favola
    
    //CREO IL CARRELLO
    $carrello = new Carrello();
    //Creo anche una variabile di sessione che fa da counter per le cassette personalizzate
    if(!isset($_SESSION['count_personalizzate'])){
        $_SESSION['count_personalizzate'] = 0;
    }
        
    
    //ASCOLTATORI per la composizione dell'ordine
     
    //ascoltatore per la cassetta già confezionata
     if(isset($_POST['aggiungi-cassetta'])){
        
         $id_cassetta = strip_tags($_GET['cassetta']);
         $qt_cassetta = strip_tags((isset($_POST['qt-cassetta'])) ? trim($_POST['qt-cassetta']) : '');
         
        
         //ora devo inserire i valori nel carrello
         //l'articolo è identificato da tre valori, identificati dell'articolo, la tabella di appartenenza (Casette, Frutta_Verdura, Aggiunta_Cassetta) 
         //e un identificativo univoco riferito alla sessione che mi indica l'ordine in questione
         
         $cassetta = new Cassetta($wpdb);
         $v_cassetta = new ViewCassetta($cassetta);
         
         $articolo_ordine = new ArticoloOrdine(session_id(), 0, $id_cassetta);         
         $articolo_ordine->setQuantita($qt_cassetta);
         $articolo_ordine->setCassettaPersonalizzata(0); //indico che la cassetta non è personalizzata
         $articolo_ordine->setPrezzoUnitario($v_cassetta->getPrezzo($id_cassetta)); //imposto il prezzo
         
         //ho creato l'elemento, ora lo salvo nel carrello
         if($carrello->isCarrelloInizializzato()){
             //controllo se il carrello esiste             
             $carrello->aggiungiArticolo($articolo_ordine);
         }
     }
     
     //ascoltatore composizione cassetta
     if(isset($_POST['aggiungi-ordine'])){
         //ottengo la variabile che mi indica i prodotti aggiunti alla cassetta
         $num_prodotti = $_POST['num-prodotti'];
         //var_dump($num_prodotti);
         if($num_prodotti > 0){
             //ho dei prodotti da salvare nel carrello
             //ottengo mediante un ciclo tutti prodotti inseriti
             //NB. il ciclo parte dal valore 1
             $i=1;
             //creo un identificativo di sessione per il tipo di cassetta personalizzata
             $_SESSION['count_personalizzate']++;
             while($i <= $num_prodotti){
                 $id_tabella = strip_tags((isset($_POST['id-tabella-'.$i])) ? trim($_POST['id-tabella-'.$i]) : '');
                 $id_prodotto = strip_tags((isset($_POST['id-prodotto-'.$i])) ? trim($_POST['id-prodotto-'.$i]) : '');
                 $qt = strip_tags((isset($_POST['qt-'.$i])) ? trim($_POST['qt-'.$i]) : '');
                
                 //aggiungo i prodotti a seconda della tabella in cui risiedono
                 $articolo_ordine = new ArticoloOrdine(session_id(), $id_tabella, $id_prodotto);
                 $articolo_ordine->setQuantita($qt);                    
                 $articolo_ordine->setCassettaPersonalizzata($_SESSION['count_personalizzate']);  //setto anche la cassetta personalizzata
                 
                 if($id_tabella == 1){
                     //Tabella Frutta_Verdura               
                     $frutta_verdura = new Frutta_Verdura($wpdb);
                     $v_frutta_verdura = new ViewFrutta_Verdura($frutta_verdura);                                          
                     $articolo_ordine->setPrezzoUnitario($v_frutta_verdura->getPrezzo($id_prodotto)); //imposto il prezzo unitario
                     //ho creato l'elemento, ora lo salvo nel carrello
                    
                 }
                 else if($id_tabella == 2){
                     //Tabella Aggiunta_cassetta
                     $aggiunta_cassetta = new Aggiunta_Cassetta($wpdb);
                     $v_aggiunta_cassetta = new ViewAggiunta_Cassetta($aggiunta_cassetta);                                          
                     $articolo_ordine->setPrezzoUnitario($v_aggiunta_cassetta->getPrezzo($id_prodotto)); //imposto il prezzo unitario                                          
                 }
                 
                  if($carrello->isCarrelloInizializzato()){
                        //controllo se il carrello esiste             
                        $carrello->aggiungiArticolo($articolo_ordine);
                    }
                 
                 $i++;
             }
         }
         else{
             //nessun prodotto :(
         }
         
     }
    //MOSTRA LA GUIDA ALGLI ACQUISTI
    _e('<div id="contenitore-guida">');
    _e('    <div id="step1" class="step active">');
    _e('     <h3>1</h3> <h4>SCEGLI LA CASSETTTA</h4>');
    _e('     <p>Aggiungi all\'ordine una cassetta già fatta oppure componila con i prodotti che vuoi.</p>');
    _e('    </div>');
    _e('    <div class="middle-step"></div>');
    _e('    <div id="step2" class="step">');
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
    include 'fai_la_spesa_1_carrello.php';
             
     ///FINE ASCOLTATORI
     
     
     _e('<h2>- Scegli la cassetta -</h2>');
    //Primo step, visualizzazione delle tre cassette
    
    //istanzio l'oggetto cassetta
    $cassetta = new Cassetta($wpdb);
    $v_cassetta = new ViewCassetta($cassetta);
    //ottengo tutte le cassette presenti nel db
    $cassette = $v_cassetta->getCassette();
    
    
    
    //CHIAMATA A DB CHE OTTIENE TUTTE LE CASSETTE DALLA TABELLA CASSETTA
    $nuove_cassette = $v_cassetta->getCassetteVetrina();
    
    //mi ritorna un array di oggetti così formato
    //obj->ID 
    //obj->Tipologia_Cassetta
    //obj->Prezzo
    //obj->ID_Foto
      
    if(count($nuove_cassette)>0){
        //ho delle cassette
        
         _e('<div id="contenitore-cassette">');
         
         $count_cassette = 0;
         $zoom_cassetta = 100 / count($nuove_cassette);
         while($count_cassette < count($nuove_cassette)){
             _e('<div class="cassetta">');
             //inserisco l'immagine della cassetta
             $foto_cassetta = $path.'cassetta-piena.jpg';
	     
                 _e('<img src="'.$foto_cassetta.'">');
            
             
             
             //inserisco il tipo di cassetta
              _e('<div><span><a target="_blank" href="http://alexsoluzioniweb.it/progetti/fdv/prodotti/cassette/">'.$nuove_cassette[$count_cassette]->Tipologia_Cassetta.'</a></span>
                    <form action="'.$url.'cassetta='.urlencode($nuove_cassette[$count_cassette]->ID).'" method="post">
                    <table>
                        <tr>                            
                            <td valign="bottom" class="td-right" >Qt. <input class="qt-cassetta" type="text" name="qt-cassetta" value="1"></td>
                            <td valign="bottom" class="td-right" ><span>€ '.$nuove_cassette[$count_cassette]->Prezzo.'</span></td>
                            <td class="td-right"><input class="aggiungi" type="submit" name="aggiungi-cassetta" value="Aggiungi"></td>
                        </tr>
                   </table>
                   </form>
               </div>');
            
           _e('</div>'); //fine div->class:cassetta
             
             $count_cassette++;
         }
         _e('   <div class="clear"></div>');
         _e('</div>'); //fine div->id:contenitore-cassette
    }
    else{
        //non ho cassette
        
        _e('Non sono presenti cassette per questo ordine');
    }
    
    //FINE CASSETTE GIA' FATTE
    
    
    //INIZIO CASSETTE PERSONALIZZATE
    
   _e('<h2 class="oppure">- Oppure -</h2>');
   _e('<h2>- Componi la tua cassetta -</h2>');
   _e('<div id="cassetta-vuota">');
   _e('     <form action="'.$url.'" method="post">');
   _e('         <div class="div-33">');
   
    $foto_cassetta_vuota = $path.'cassetta-vuota.png';
   
    if(image_exists($foto_cassetta_vuota)){                                                                      

        _e('<img src="'.$foto_cassetta_vuota.'">');
     }
     else{
        _e('<img src="'.$path.'non-disponibile.jpg" >');
     }
   _e('<div id="num-prodotti"></div>');  
   _e('     </div>');  //fine div->33%
   
   _e('     <div class="div-66">');
       _e('     
                    <div id="prodotti-aggiunti"></div>
                    <div class="clear"></div>                    
                                        
                    <input type="text" value="1" name="cassetta-personalizzata" style="display:none">
                    
                ');
  
   _e('<input class="aggiungi clear" type="submit" value="Aggiungi Cassetta Personalizzata" name="aggiungi-ordine">     </div></form>'); //dine div->66%
   
   _e('</div>'); //fine div->id:cassetta-vuota
   _e('<div class="clear"></div>');
   _e('<div id="frutta-verdura">');
   _e('<h3 style="text-align:center;">Frutta e Verdura</h3>');
   
   
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
                        
                         //ottengo le coordinate dell'immagine relativa nello sprite
                         $coord = getCoordinateSprite($fv_prodotti[$fv_count]->ID_Foto);         
                        
                        _e('<div id="prod-1-'.$fv_prodotti[$fv_count]->ID.'" class="prodotto ');
                        if($fv_count % 2 == 0){ _e(' pari">'); }else{ _e(' dispari">');}
                        _e('    <table cellpadding="3" >');
                        
                        
                        //$url_griglia = plugins_url().'/gestione_ordine/css/griglia_fv.png';
                        //scrivo la riga
                        _e('        <tr>');
                        
                        _e('            <td rowspan="2" class="td-immagine"><div class="fv-immagine-prodotto" style="background-position:-'.$coord['left'].'px -'.$coord['top'].'px"></div></td>'
                         . '            <td colspan="3" class="td-titolo">'.$fv_prodotti[$fv_count]->Nome_Prodotto.'</td>'
                         . '        </tr>');
                         _e('       <tr valign="bottom">');
                       
                        _e('            <td><strong>€ '.$fv_prodotti[$fv_count]->Prezzo.'</strong> <span style="font-size:0.8em">a '.$fv_prodotti[$fv_count]->Unita.'</span></td>'
                          .'            <td><input id="prodotto-qt-1-'.$fv_prodotti[$fv_count]->ID.'" class="qt-prodotto" type="text" value="1" name="quantita"></td>'
                          . '       <td rowspan="2" ><input title="Aggiungi alla cassetta" class="add-button" id="button-add-1-'.$fv_prodotti[$fv_count]->ID.'" onClick="add_Prodotto(1, '.$fv_prodotti[$fv_count]->ID.', '.$coord['top'].', '.$coord['left'].', \''.$fv_prodotti[$fv_count]->Nome_Prodotto.'\', \''.$fv_prodotti[$fv_count]->Prezzo.'\', \''.$fv_prodotti[$fv_count]->Unita.'\')" type="button" name="aggiungi-frutta" value=""></td></tr>');
                        _e('    </table>');
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
                _e('   <h4 style="height:20px;">'.$ac_types[$ac_types_count].'</h4>');
                $ac_count = 0;
               
                while($ac_count < count($ac_prodotti)){                    
                    
                    //ottengo le coordinate dell'immagine relativa nello sprite
                    $coord = getCoordinateSprite($ac_prodotti[$ac_count]->ID_Foto);                    
                  
                    if($ac_prodotti[$ac_count]->Tipologia_Prodotto == $ac_types[$ac_types_count]){
                        
                        //ho un riscontro tra la tipologia presente ed il prodotto
                        _e('<div id="prod-2-'.$ac_prodotti[$ac_count]->ID.'" class="prodotto ');
                        if($ac_count % 2 == 0){ _e('pari">');} else {_e('dispari">');}
                        _e('    <table cellpadding="3" >');
                        
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
                          .'            <td><input id="prodotto-qt-2-'.$ac_prodotti[$ac_count]->ID.'"  class="qt-prodotto" type="text" value="1" name="quantita"></td>'
                          . '       <td rowspan="2" ><input title="Aggiungi alla cassetta" class="add-button" id="button-add-2-'.$ac_prodotti[$ac_count]->ID.'" onClick="add_Prodotto(2, '.$ac_prodotti[$ac_count]->ID.', '.$coord['top'].', '.$coord['left'].', \''.$ac_prodotti[$ac_count]->Nome_Prodotto.'\', \''.$ac_prodotti[$ac_count]->Prezzo.'\', \''.$ac_prodotti[$ac_count]->Unita.'\')" type="button" name="aggiungi-frutta" value=""></td></tr>');
                        _e('    </table>');
                        _e('</div>');
                        
                    }
                    else{
                         
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