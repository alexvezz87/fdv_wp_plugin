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
    $title_mail = "Frutti da Favola - Riepilogo Ordine";
    $msg = "";
    
    
    if(count($riepilogo) > 0){
        //non ho errori nella variabile di sessione che contiene i dati di chi ha effettauto l'ordine
        $msg .= "<h3>Ordine Registrato</h3>
            Gentile cliente,<br>
            La ringraziamo per aver scelto Frutti da Favola.<br>
            Il suo ordine &egrave; stato registrato.<br>
            Il nostro staff sta controllando e preparando i prodotti richiesti per la spedizione.<br>
            Se &egrave; un utente gi&agrave; registrato pu&ograve; consultare i dettagli dell'ordine all'interno dell'area riservata.<br>
            Vi ricordiamo che la consegna avverrà il Martedì o il Mercoledì della settimana prossima.<br><br>
            Per qualsiasi informazione pu&ograve; inviarci una mail all'indirizzo <a href=\"mailto:info@fruttidafavola.it\">info@fruttidafavola.it</a><br>
            Oppure telefonarci al numero +39 393 9164986.<br><br>";
        
        $msg.= "<h3>Riepilogo Ordine</h3>";
        //carico le classi dei prodotti
        $cassetta = new Cassetta($wpdb);
        $v_cassetta = new ViewCassetta($cassetta);
        $frutta_verdura = new Frutta_Verdura($wpdb);
        $v_frutta_verdura = new ViewFrutta_Verdura($frutta_verdura);
        $aggiunta_cassetta = new Aggiunta_Cassetta($wpdb);
        $v_aggiunta_cassetta = new ViewAggiunta_Cassetta($aggiunta_cassetta);            

        $spesa_totale = 0;

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
        $msg.="<br><br>";
        //Dati di Spedizione

        $msg.="I prodotti saranno spediti a:<br>";
        $msg.="<span style=\"font-size:1.3em\">".$riepilogo['nominativo']."</span><br>";
        $msg.="<div style=\"margin-top:15px\">";
        $msg.="<table style=\"margin-left:10px\" cellpadding=\"5\">";
        $msg.="<tr><td>Indirizzo:</td><td>".$riepilogo['indirizzo']['Via']."</td></tr>"; 
        $msg.="<tr><td>Civico:</td><td>".$riepilogo['indirizzo']['Civico']."</td></tr>";
        $msg.="<tr><td>CAP:</td><td>".$riepilogo['indirizzo']['CAP']."</td></tr>";
        $msg.="<tr><td>Citt&agrave;:</td><td>".$riepilogo['indirizzo']['Citta']."</td></tr>";
        $msg.="<tr><td>Provincia:</td><td>".$riepilogo['indirizzo']['Prov']."</td></tr>";
        $msg.="<tr><td>Note:</td><td>".$riepilogo['note']."</td></tr>";
        $msg.="</table>";

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


    //            $header = "From: \"Ordine Frutti da Favola\" <info@fruttidafavola.it>\r\n";
    //            $header .= "MIME-Version: 1.0\n";
    //            $header .= "Content-Type: text/html; charset=\"iso-8859-1\"\n";
    //            $header .= "Content-Transfer-Encoding: 7bit\n\n";

        if(checkEmail($riepilogo['email'])){
            add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));
            if(!wp_mail($riepilogo['email'], $title_mail, $msg)){

                print"<script language=\"javascript\">
                alert(\"Errore nell'invio del messaggio!\");
                </script>";
            }
            else{
                $sent_mail = true;
                $invio_cliente = true;
            }
        }
        
    }
    else{
        //ho errori nella variabile di sessione che contiene i dati di chi ha effettauto l'ordine
        //se entro in questa condizione vuol dire che la variabile di sessione non contiene i dati di riepilogo e quindi non ho i dati per inviare la mail
        //la mail non arriverà e verrà restituito errore.
        //La cosa viene gestita con la mail inviata all'amministratore, precedentemente composta e inviata.
        $sent_mail = false;
        $invio_cliente = false;
    }
    

?>