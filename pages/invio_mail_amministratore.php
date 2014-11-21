<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/
    

//    $msg = "<html>
//                    <head>
//                    <title>Ordine ricevuto da ".$riepilogo['nominativo']."</title>
//                    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
//                    </head>
//                    <body>";
//                    

    $title_mail = "";
    $msg = "";
    if(count($riepilogo) > 0){
        //non ho errori nella variabile di sessione che contiene i dati di chi ha effettauto l'ordine

        if($riepilogo['nominativo'] != null && $riepilogo['nominativo'] != ""){
            $title_mail .= "Ordine ricevuto da ".$riepilogo['nominativo'];	
            $msg .= "<h3>Ordine ricevuto da ".$riepilogo['nominativo']."</h3>";
        }
        else{
            $title_mail .= "Ordine ricevuto da un cliente";	
            $msg .= "<h3>Ordine ricevuto da un cliente</h3><p>Il nominativo dell'utente non è stato registrato correttamente. Controllare i dati su riepilogo ordine.</p>";
        }
        $msg.="<h3>Riepilogo Ordine</h3>";

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
                    }
                    else if($temp_articolo_ordine->getID_Tabella() == 2){
                        //Aggiunta_Cassetta
                        $msg.="<tr>"
                                . "<td>".$temp_articolo_ordine->getQuantita()." ".$v_aggiunta_cassetta->getUnita($temp_articolo_ordine->getID_Prodotto())."</td>"
                                . "<td>".$v_aggiunta_cassetta->getNomeProdotto($temp_articolo_ordine->getID_Prodotto())."</td>"
                                . "<td>&#8364; ".$v_aggiunta_cassetta->getPrezzo($temp_articolo_ordine->getID_Prodotto())."</td>"
                            . "</tr>";
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
                }
                else if($temp_articolo_ordine->getID_Tabella() == 1){
                    //Frutta_Verdura
                     $msg.="<tr>"
                                . "<td>".$temp_articolo_ordine->getQuantita()." ".$v_frutta_verdura->getUnita($temp_articolo_ordine->getID_Prodotto())."</td>"
                                . "<td>".$v_frutta_verdura->getNomeProdotto($temp_articolo_ordine->getID_Prodotto())."</td>"
                                . "<td>&#8364; ".$v_frutta_verdura->getPrezzo($temp_articolo_ordine->getID_Prodotto())."</td>"
                            . "</tr>";
                }
                else if($temp_articolo_ordine->getID_Tabella() == 2){
                    //Aggiunta_Cassetta
                    $msg.="<tr>"
                                . "<td>".$temp_articolo_ordine->getQuantita()." ".$v_aggiunta_cassetta->getUnita($temp_articolo_ordine->getID_Prodotto())."</td>"
                                . "<td>".$v_aggiunta_cassetta->getNomeProdotto($temp_articolo_ordine->getID_Prodotto())."</td>"
                                . "<td>&#8364; ".$v_aggiunta_cassetta->getPrezzo($temp_articolo_ordine->getID_Prodotto())."</td>"
                            . "</tr>";
                }
                $z++;
            }
            $msg.="</table>";
        }

        $msg.="<br>";
        $msg.="<h4>Contatti</h4>";
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
        $msg.="</div>";


        add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));
        if(!wp_mail('alexvezz87@gmail.com', $title_mail, $msg)){   //PARAMETRO DA CAMBIARE

        }
        else{
            $invio_amministratore = true;
        }

    }
    else{
        //ho errori nella variabile di sessione che contiene i dati di chi ha effettauto l'ordine
        //invio una mail di sostituzione
        $title_mail = "Ordine ricevuto da un cliente";
        $msg = "Il sistema non è riuscito ad ottenere tutti i dati necessari per inviare la mail.<br>Controllare il riepilogo ordini su fruttidafavola.it e ricontattare il cliente per l'avvenuto ordine.";
        if(!wp_mail('alexvezz87@gmail.com', $title_mail, $msg)){   //PARAMETRO DA CAMBIARE

        }
        else{
            $invio_amministratore = true;
        }
	sleep(1);    
    }


           //print_r($msg);
          
//            $header = "From: \"Servizio Ordini\" <ordini@fruttidafavola.it>\r\n";
//            $header .= "MIME-Version: 1.0\n";
//            $header .= "Content-Type: text/html; charset=\"iso-8859-1\"\n";
//            $header .= "Content-Transfer-Encoding: 7bit\n\n";

?>