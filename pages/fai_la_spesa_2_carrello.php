<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

$url = curPageURL();
$temp_url = explode('&tabella', $url);

if($temp_url != false && count($temp_url)>0){
    $url = $temp_url[0];
}

$temp_url = explode('&cp', $url);
if($temp_url != false && count($temp_url)>0){
    $url = $temp_url[0];
}

if(strpos($url, '?')!=false){
    $url .= "&";
}
else{
    $url .= "?";
}
        
//Ascoltatore per modifiche agli articoli del carrello
if(isset($_POST['elimina-da-carrello'])){
    $id_tabella = $_GET['tabella'];
    $id_prodotto = $_GET['prodotto'];
    
    //cancello
    if($carrello->rimuoviArticolo($id_tabella, $id_prodotto)!=true){
        _e('...troubles :(');
    }   
} 

if(isset($_POST['elimina-cp'])){
    $cp = $_GET['cp'];
    
    //cancello
    if($carrello->rimuoviCassettaPersonalizzata($cp)!= true){
        _e('..troubles :(');
    }
    else{
        $_SESSION['count_personalizzate']--;
        $carrello->resetCP($cp);
    }
    
}
        
                 

if($carrello->isCarrelloInizializzato() && !$carrello->isCarrelloEmpty()){
    _e('<div id="visulizza-carrello">');
        _e('<span class="titolo-carrello">Riepilogo Ordine</span><br><br>');
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
                _e( '<form action="'.$url.'tabella='.$temp_articolo_ordine->getID_Tabella().'&prodotto='.$temp_articolo_ordine->getID_Prodotto().'" method="post">');
                _e(     '<input title="Rimuovi dal Carrello" class="remove-button" style="float:right;" type="submit" value="" title="Rimuovi dal carrello" name="elimina-da-carrello">');
                _e(     $foto_da_inserire);
                _e(        '<span style="font-size:1em;">'.$temp_articolo_ordine->getQuantita().' x '.$info_cassetta->Tipologia_Cassetta.' - € '.$info_cassetta->Prezzo.'</span>');
                           
                _e( '</form>');
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
                
                _e('<div class="prodotto visualizza-prodotto ');
                if( ($i  % 2 == 0)){_e('pari"');} else{_e('dispari"');}
                _e('>');
                _e( '<form action="'.$url.'tabella='.$temp_articolo_ordine->getID_Tabella().'&prodotto='.$temp_articolo_ordine->getID_Prodotto().'" method="post">');
                _e(     '<input title="Rimuovi dal Carrello" class="remove-button" style="float:right" type="submit" value="" name="elimina-da-carrello">');
                _e(     '<div class="fv-immagine-prodotto" style="background-position:-'.$coord['left'].'px -'.$coord['top'].'px; float:left"></div>');
                _e(     '<div class="descrizione">'.$temp_articolo_ordine->getQuantita().' '.$fv_info->Unita.' '.$fv_info->Nome_Prodotto.'<br>€ '.$fv_info->Prezzo.'</div>');
                _e( '</form>');
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
                
                _e('<div class="prodotto visualizza-prodotto ');
                if( ($i  % 2 == 0)){_e('pari"');} else{_e('dispari"');}
                _e('>');
                _e( '<form action="'.$url.'tabella='.$temp_articolo_ordine->getID_Tabella().'&prodotto='.$temp_articolo_ordine->getID_Prodotto().'" method="post">');
                _e(     '<input title="Rimuovi dal Carrello" class="remove-button" style="float:right" type="submit" value="" name="elimina-da-carrello">');
                _e(     '<div class="ac-immagine-prodotto" style="background-position:-'.$coord['left'].'px -'.$coord['top'].'px; float:left"></div>');
                _e(     '<div class="descrizione">'.$temp_articolo_ordine->getQuantita().' '.$ac_info->Unita.' '.$ac_info->Nome_Prodotto.'<br>€ '.$ac_info->Prezzo.'</div>');
                _e( '</form>');
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
                   _e('<div class="prodotto visualizza-cassetta-personalizzata ');
                   if( ($i  % 2 == 0)){_e('pari"');} else{_e('dispari"');}
                   _e('><form action="'.$url.'cp='.$temp_articolo_ordine->getCassettaPersonalizzata().'" method="post"><input title="Rimuovi dal Carrello" class="remove-button" style="float:right" type="submit" value="" name="elimina-cp"><div class="clear" style="font-weight:bold">1 x Cassetta Personalizzata '.$k.'</div></form>');
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
    _e('<div class="clear" style="text-align:right; font-size:1.4em">');
    if((float)$totale_ordine < 12){
        _e('Totale carrello: <span style="font-weight:bold; color:red">€ '. number_format((float)$totale_ordine,2).'</span>');
        _e('<br><span style="font-size:0.8em">[spesa minima <strong>€ 12.00</strong>]</span>');
    }
    else{
        _e('Totale carrello: <strong>€ '. number_format((float)$totale_ordine,2).'</strong>');
    }    
    _e('<div style="margin-top:10px; margin-bottom:15px;">');
    if((float)$totale_ordine >= 12){
        _e('<input class="aggiungi" style="float:right" onClick="location.href=\'http://alexsoluzioniweb.it/progetti/fdv/fai-la-spesa/step-3/\'" type="button" value="CONTINUA >> ">');
    }
    _e('<input class="aggiungi" style="float:left" onClick="location.href=\'http://alexsoluzioniweb.it/progetti/fdv/fai-la-spesa/step-1/\'" type="button" value="<< INDIETRO"></div>');
    _e('<div class="clear"></div></div>');
    _e('</div>');
    
    
 }


?>