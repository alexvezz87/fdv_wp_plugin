<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

    $title_admin = "";
    $title_cliente = "";
    $msg_admin = "";
    $msg_cliente = "";
    $sent_notifica_admin = false;
    $sent_notifica_cliente = false;
    
    
    //invio la mail di debug all'amministratore
    $title_my_admin = "Debug Ordine Frutti da Favola";
    $msg_my_admin = print_r($riepilogo, true);
    wp_mail('alexvezz87@gmail.com', $title_my_admin, $msg_my_admin);
    sleep(1);

    if(count($riepilogo) > 0){
        //non ho errori nella variabile di sessione che contiene i dati di chi ha effettauto l'ordine
        $title_admin .= "Ordine ricevuto da ".$riepilogo['nominativo'];
        $msg_admin .= "E' stato ricevuto un nuovo ordine da ".$riepilogo['nominativo'];
        if(wp_mail('alexvezz87@gmail.com', $title_admin, $msg_admin)){
            $sent_notifica_admin = true;
        }
	sleep(1);
        
        
        $title_cliente .= "Frutti da Favola - Ordine Registrato";
        $msg_cliente .= "Il tuo ordine presso il nostro servizio online è stato registrato correttamente!\n\nCordiali saluti\nLo staff di Frutti da Favola";
        if(checkEmail($riepilogo['email'])){           
            if(wp_mail($riepilogo['email'], $title_cliente, $msg_cliente)){
              $sent_notifica_cliente = true;
            }
	    sleep(1);
        }
        
        if($sent_notifica_admin && $sent_notifica_cliente){
            $sent_notifica = true;
        }        
    }
    else{
        //ho errori nella variabile di sessione che contiene i dati di chi ha effettauto l'ordine
        $title_admin .= "Ordine ricevuto da ".$riepilogo['nominativo'];
        $msg_admin .= "E' stato ricevuto un nuovo ordine da ".$riepilogo['nominativo'];
        if(wp_mail('alexvezz87@gmail.com', $title_admin, $msg_admin)){
            $sent_notifica_admin = true;
        }
        sleep(1);
    }



?>