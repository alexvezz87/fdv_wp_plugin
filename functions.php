<?php

//AUTORE: Alex Vezzelli - alexsoluzioniweb.it

/*function curPageURL() {
        $pageURL = 'http';
        if (isset($_SERVER["HTTPS"])){
                if($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
        }
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
       } 
       else{ $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]; }
       
       return $pageURL;
    }
 */   
    function writeMese($num){
        //la funione restituisce una stringa che corrisponde al mese in questione
        $num = (int)$num;
        switch($num){
            case 1: return "Gennaio";
            case 2: return "Febbraio";
            case 3: return "Marzo";
            case 4: return "Aprile";
            case 5: return "Maggio";
            case 6: return "Giugno";
            case 7: return "Luglio";
            case 8: return "Agosto";
            case 9: return "Settembre";
            case 10: return "Ottobre";
            case 11: return "Novembre";
            case 12: return "Dicembre";
            default: return "errore";
        }    
        
        
    }
    
    function checkEmail($email){
	// elimino spazi, "a capo" e altro alle estremità della stringa
        $email = trim($email);

        // se la stringa è vuota sicuramente non è una mail
        if(!$email) {
                return false;
        }

        // controllo che ci sia una sola @ nella stringa
        $num_at = count(explode( '@', $email )) - 1;
        if($num_at != 1) {
                return false;
        }

        // controllo la presenza di ulteriori caratteri "pericolosi":
        if(strpos($email,';') || strpos($email,',') || strpos($email,' ')) {
                return false;
        }

        // la stringa rispetta il formato classico di una mail?
        if(!preg_match( '/^[\w\.\-]+@\w+[\w\.\-]*?\.\w{1,4}$/', $email)) {
                return false;
        }

        return true;
    }
    
    function image_exists($url) {
        if(getimagesize($url))
            return(TRUE);
        else
            return(FALSE);
    }
    
    function getCoordinateSprite($id_foto){
        //spacco il campo per sapere il valore di id
        $temp_id_foto = explode('-', $id_foto);
        $count_foto = $temp_id_foto[0];        

        //Lo sprite è suddiviso in una matrice nx10, dove n sono le righe e 10 sono le colonne
        //Ad ogni multiplo di 10 devo resettare l'id della colonna

        //imposto i campi standard di altezza e lunghezza di ogni elemento dello sprite
        $altezza = 61;
        $lunghezza = 91;

        //trovo i valori di riga e colonna conoscendo il count_foto
        //tratto count_foto come una stringa che contiene 2 campi, il primo è il numero della riga, il secondo quello della colonna.
        $riga = substr($count_foto, 0,1);
        $colonna = substr($count_foto, 1,2);

        $coord['top'] = ((int)$riga * $altezza)+1;
        $coord['left'] = ((int)$colonna * $lunghezza);
        
        return $coord;
        
    }
    
    /**
     * Funzione che prese in ingresso gli array delle cassette, restituisce un messaggio e il calcolo della spesa totale
     * @param type $cps
     * @param type $no_cp
     * @return type
     */
    function getCassette($cps, $no_cp){
        global $wpdb;
        $msg = "";
        $spesa_totale = 0;
        
        //carico le classi dei prodotti
        $cassetta = new Cassetta($wpdb);
        $v_cassetta = new ViewCassetta($cassetta);
        $frutta_verdura = new Frutta_Verdura($wpdb);
        $v_frutta_verdura = new ViewFrutta_Verdura($frutta_verdura);
        $aggiunta_cassetta = new Aggiunta_Cassetta($wpdb);
        $v_aggiunta_cassetta = new ViewAggiunta_Cassetta($aggiunta_cassetta);

        if(count($cps) > 0){
            //ho delle cassette personalizzate
            $msg.="<h4>Cassette Personalizzate</h4>";
            $k=0;
            while($k < count($cps)){
                $msg.="<span style=\"font-size:1.2em; font-weight:bold\">Cassetta Personalizzata ".($k+1)."</span><br>";
                $msg.="<table style=\"margin-left:20px\">";
                $j=0;
                while($j < count($cps[$k])){
                    $temp_articolo_ordine = new ArticoloOrdine(null, null, null);
                    $temp_articolo_ordine = $cps[$k][$j];
                    if($temp_articolo_ordine->getID_Tabella() == 1){
                        //Frutta_Verdura
                        $msg.="<tr>"
                                . "<td>".$temp_articolo_ordine->getQuantita()." ".$v_frutta_verdura->getUnita($temp_articolo_ordine->getID_Prodotto())."</td>"
                                . "<td>".$v_frutta_verdura->getNomeProdotto($temp_articolo_ordine->getID_Prodotto())."</td>"
                                . "<td>&#8364; ".$v_frutta_verdura->getPrezzo($temp_articolo_ordine->getID_Prodotto())."</td>"
                            . "</tr>";
                        $spesa_totale += $temp_articolo_ordine->getQuantita() * $v_frutta_verdura->getPrezzo($temp_articolo_ordine->getID_Prodotto());
                    }
                    else if($temp_articolo_ordine->getID_Tabella() == 2){
                        //Aggiunta_Cassetta
                        $msg.="<tr>"
                                . "<td>".$temp_articolo_ordine->getQuantita()." ".$v_aggiunta_cassetta->getUnita($temp_articolo_ordine->getID_Prodotto())."</td>"
                                . "<td>".$v_aggiunta_cassetta->getNomeProdotto($temp_articolo_ordine->getID_Prodotto())."</td>"
                                . "<td>&#8364; ".$v_aggiunta_cassetta->getPrezzo($temp_articolo_ordine->getID_Prodotto())."</td>"
                            . "</tr>";
                        $spesa_totale += $temp_articolo_ordine->getQuantita() * $v_aggiunta_cassetta->getPrezzo($temp_articolo_ordine->getID_Prodotto());
                    }
                    $j++;
                }
                $msg.="</table>";
                $k++;
            }               
        }

        if(count($no_cp)>0){
            //ho dei prodotti diversi dalle cassette personalizzate
            $msg.="<h4>Altri Prodotti</h4>";
            $z=0;
            $msg.="<table style=\"margin-left:20px\">";
            while($z < count($no_cp)){
                $temp_articolo_ordine = new ArticoloOrdine(null, null, null);
                $temp_articolo_ordine = $no_cp[$z];

                if($temp_articolo_ordine->getID_Tabella() == 0){
                    //Cassetta
                    $msg.="<tr>"
                            . "<td>".$temp_articolo_ordine->getQuantita()." X </td>"
                            . "<td>".$v_cassetta->getTipologiaCassetta($temp_articolo_ordine->getID_Prodotto())."</td>"
                            . "<td>&#8364; ".$v_cassetta->getPrezzo($temp_articolo_ordine->getID_Prodotto())."</td>"
                        . "</tr>";
                    $spesa_totale += $temp_articolo_ordine->getQuantita() * $v_cassetta->getPrezzo($temp_articolo_ordine->getID_Prodotto());
                }
                else if($temp_articolo_ordine->getID_Tabella() == 1){
                    //Frutta_Verdura
                     $msg.="<tr>"
                                . "<td>".$temp_articolo_ordine->getQuantita()." ".$v_frutta_verdura->getUnita($temp_articolo_ordine->getID_Prodotto())."</td>"
                                . "<td>".$v_frutta_verdura->getNomeProdotto($temp_articolo_ordine->getID_Prodotto())."</td>"
                                . "<td>&#8364; ".$v_frutta_verdura->getPrezzo($temp_articolo_ordine->getID_Prodotto())."</td>"
                            . "</tr>";
                     $spesa_totale += $temp_articolo_ordine->getQuantita() * $v_frutta_verdura->getPrezzo($temp_articolo_ordine->getID_Prodotto());
                }
                else if($temp_articolo_ordine->getID_Tabella() == 2){
                    //Aggiunta_Cassetta
                    $msg.="<tr>"
                                . "<td>".$temp_articolo_ordine->getQuantita()." ".$v_aggiunta_cassetta->getUnita($temp_articolo_ordine->getID_Prodotto())."</td>"
                                . "<td>".$v_aggiunta_cassetta->getNomeProdotto($temp_articolo_ordine->getID_Prodotto())."</td>"
                                . "<td>&#8364; ".$v_aggiunta_cassetta->getPrezzo($temp_articolo_ordine->getID_Prodotto())."</td>"
                            . "</tr>";
                    $spesa_totale += $temp_articolo_ordine->getQuantita() * $v_aggiunta_cassetta->getPrezzo($temp_articolo_ordine->getID_Prodotto());
                }                    
                $z++;
            }
            $msg.="</table>";
        }
        
        $result = array();
        $result['msg'] = $msg;
        $result['tot'] = $spesa_totale;
        return $result;
    }
    
    function printContatti($riepilogo){
        $msg = "";
        $msg.="<span style=\"font-size:1.3em\">".$riepilogo['nominativo']."</span><br>";
        $msg.="<div style=\"margin-top:15px\">";
        $msg.="<table style=\"margin-left:10px\" cellpadding=\"5\">";
        $msg.="<tr><td>Indirizzo:</td><td>".$riepilogo['indirizzo']['Via']."</td></tr>"; 
        $msg.="<tr><td>Civico:</td><td>".$riepilogo['indirizzo']['Civico']."</td></tr>";
        $msg.="<tr><td>CAP:</td><td>".$riepilogo['indirizzo']['CAP']."</td></tr>";
        $msg.="<tr><td>Citt&agrave;:</td><td>".$riepilogo['indirizzo']['Citta']."</td></tr>";
        $msg.="<tr><td>Provincia:</td><td>".$riepilogo['indirizzo']['Prov']."</td></tr>";
        $msg.="<tr><td>Telefono</td><td>".$riepilogo['telefono']."</td></tr>";
        $msg.="<tr><td>Cellulare:</td><td>".$riepilogo['cellulare']."</td></tr>";
        $msg.="<tr><td>Note:</td><td>".$riepilogo['note']."</td></tr>";
        $msg.="</table>";
        return $msg;
    }
    
    
    
    /**
     *  Funzione che invia la mail con i dovuti controlli per il debug
     * 
     * @param type $destinatario
     * @param type $riepilogo
     * @param type $html_flag
     * @return boolean
     */
    function inviaMail($destinatario, $riepilogo, $html_flag){
       
        //a seconda del destinatario viene inviata una mail diversa
        $titolo = "";
        $msg = "";
        $info_cassette = array();
        
        
        $cps = $riepilogo['cps'];
        $no_cp = $riepilogo['no_cp'];
       
        
        
        switch($destinatario){
            case 'alexvezz87@gmail.com' :
                //invio mail di debug
                $titolo = "Debug Ordine Frutti da Favola";
                $msg .= print_r($riepilogo, true);
                break;
            
            case 'info@alexsoluzioniweb.it' :
                //invio mail a frutti da favola                
                         
                $titolo .= "Riepilogo ordine di ".$riepilogo['nominativo'];	
                $msg .= "<h3>Ordine ricevuto da ".$riepilogo['nominativo']."</h3>";
                $msg.="<h3>Riepilogo Ordine</h3>";               
                    
                $info_cassette = getCassette($cps, $no_cp);
                $msg.= $info_cassette['msg'];

                $msg.="<br>";
                $msg.="<h4>Contatti</h4>";
                $msg.= printContatti($riepilogo);
                $msg.="</div>";
                
                break;
            default :
                //invio mail a cliente
                $titolo .= "Frutti da Favola - Riepilogo Ordine";
                
                $msg .= "<h3>Ordine Registrato</h3>
                        Gentile cliente,<br>
                        La ringraziamo per aver scelto Frutti da Favola.<br>
                        Il suo ordine &egrave; stato registrato.<br>
                        Il nostro staff sta controllando e preparando i prodotti richiesti per la spedizione.<br>
                        Se &egrave; un utente gi&agrave; registrato pu&ograve; consultare i dettagli dell'ordine all'interno dell'area riservata.<br>
                        Per la zona di Bologna le consegne avvengono utti i lunedì dalle 18,30 alle 20,30<br>
                        Per la zona di Modena,Carpi,Vignola, Spilamberto, Sassuolo e limitrofi la consegna avviene il martedì dalle 18,30 alle 20,30.<br><br>
                        Per qualsiasi informazione pu&ograve; inviarci una mail all'indirizzo <a href=\"mailto:info@fruttidafavola.it\">info@fruttidafavola.it</a><br>
                        Oppure telefonarci al numero +39 393 9164986.<br><br>";
        
                $msg.= "<h3>Riepilogo Ordine</h3>";                         

                $info_cassette = getCassette($cps, $no_cp);
                $msg.= $info_cassette['msg'];
                $spesa_totale = $info_cassette['tot'];
                
                $msg.="<br><br>";
                //Dati di Spedizione

                $msg.="I prodotti saranno spediti a:<br>";
                $msg.= printContatti($riepilogo);

                $spesa_totale = number_format((float)$spesa_totale, 2);
                $msg.="<br><strong>Spesa complessiva: &#8364; ".$spesa_totale." </strong>";

                $cliente = new Cliente(null, null);
                $v_cliente = new ViewCliente($cliente);                
                $sconto_cliente = $v_cliente->getSconto($riepilogo['email']);
                if($sconto_cliente != 0 && $sconto_cliente != null && $sconto_cliente != ''){
                    $prezzo_da_scontare = ((float)$spesa_totale * (float)$sconto_cliente) / 100;
                    $totale_scontato = (float)$spesa_totale - (float)$prezzo_da_scontare;
                    if((float)$totale_scontato < 18){
                        $totale_scontato = 18;
                    }
                    $msg.= "<br><strong>Sconto: ".$sconto_cliente."%</strong>";
                    $msg.= "<br><strong>Totale ordine con sconto: € ".number_format((float)$totale_scontato, 2)."</strong>";  
                }
                $msg.="</div>";
                $msg.="<br><br>Cordiali Saluti<br>Mazzi Cristina<br>Servizio Clienti - Frutti da Favola S.A.C";
                
                break;
        }
        
        
        //invio la mail
        $mail_sent = false;
        $counter_fail = 0;        
                
        while($mail_sent == false && $counter_fail < 10){
            sleep(1);
            //se il flag che indica se il contenuto della mail è in html è vero, setto il valore per wp_mail
            if($html_flag == true){
                add_filter('wp_mail_content_type', 'set_html_content_type');
            }
            else{
                remove_filter('wp_mail_content_type', 'set_html_content_type');
            }
            if(wp_mail($destinatario, $titolo, $msg)){
                //La mail è stata spedita correttamente
                $mail_sent = true;
            }
            else{
                //Sono sopraggiunti errori nell'invio della mail
                $counter_fail++;
            }            
        }
        
        //concludo restituendo un valore
        return $mail_sent;        
        
    }

 function set_html_content_type() {
	return 'text/html';
    }  

	
?>