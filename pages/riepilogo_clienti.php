<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

//prima di elaborare delle azioni sui clienti, controllo se ho i riscontri corretti tra la tabella utenti di wordpress e le tabelle clienti e contatti del plugin
$clienti = get_users(array('role' => 'customer'));
$cliente = new Cliente(null, null);
$v_cliente = new ViewCliente($cliente);
$c_cliente = new ControllerCliente($cliente);
$usernames = $v_cliente->getUsernames();

if(count($clienti) > 0 && count($usernames)>0){
    //effettuo questa operazione quando ho dei riscontri tra le due tabelle
    $j=0;  
    while($j < count($clienti)){
        $utente = $clienti[$j];
        //print_r($utente);
        //faccio un controllo sulla tabella clienti
        $z=0;
        while($z < count($usernames)){
            if($utente->data->user_login == $usernames[$z]){
                //ottengo la mail dalla tabella clienti
                $email = $v_cliente->getEmail($usernames[$z]);                
                //se ho un riscontro effettuo un controllo sulle email
                 
                if($utente->data->user_email != $email){
                    //ho una situazione dove le mail dello stesso utente non corrispondono
                    //ciò è dovuto al fatto che l'utente ha aggiornato le informazioni del suo profilo utente
                    //ma non quelle del profilo cliente. In questo caso devo effettuare una modifica automatica per avere le mail compatibili
                   
                    //cambio in primis la mail contenuta nella tabella contatti
                    $contatti = new Contatti($email);
                    $c_contatti = new ControllerContatti($contatti);
                    $v_contatti = new ViewContatti($contatti);                    
                    
                    if(!$c_contatti->setEmail($v_contatti->isContatto($v_cliente->getEmail($usernames[$z])), $utente->data->user_email)){
                        _e('Errore nell\'aggiornare la mail a contatti');
                    }
                    
                    //poi cambio la mail della tabella clienti
                    if(!$c_cliente->setEmail($utente->data->user_login, $utente->data->user_email)){
                        _e('Errore nell\'aggiornare la mail a Clienti');
                    }
                }
                //controllo se i nomi corrispondono
                
                if($utente->data->display_name != $v_cliente->getNominativo($v_cliente->getEmail($utente->data->user_login))){
                    
                    //i nomi non corrispondono, quindi aggiorno il nominativo di cliente a quello di user (user comanda!)
                    if(!$c_cliente->setNominativo($v_cliente->getEmail($utente->data->user_login), $utente->data->display_name)){
                        _e('Errore nell\'aggiornare il nominativo cliente ');
                    }
                    
                }
            }
            $z++;
        }
        
        $j++;
    }
    
}


if(isset($_POST['aggiorna-sconto'])){
                //aggiorno lo sconto
                $email = $_GET['email'];
                $sconto = strip_tags((isset($_POST['sconto'])) ? trim($_POST['sconto']) : '');
                $cliente = new Cliente(null, null);
                $c_cliente = new ControllerCliente($cliente);
                $c_cliente->setSconto($email, $sconto);
                
            }

    _e('<h1>Riepilogo Clienti</h1>');
    
    
    //conto i clienti del sito
    $cliente = new Cliente(null, null);
    
    $v_cliente = new ViewCliente($cliente);
    
    $clienti = $v_cliente->getClienti(); //trovo un array di clienti
  
    if(count($clienti) > 0){
        //ho dei clienti
        _e(' 
            <table class="mia_tabella">
                <tr style="font-weight:bold">
                    <th>Nominativo</th>
                    <th>Email</th>
                    <th>Indirizzo</th>
                    <th>Telefono</th>
                    <th>Cellulare</th>
                    <th>Sconto</th>
                </tr>            
        ');
        
        $i=0;
        while($i < count($clienti)){
            //aggiungo i contatti
            $cliente = $clienti[$i];
            
            $contatti = new Contatti($cliente->getEmail());
            $v_contatti = new ViewContatti($contatti);
            $ind_sped = $v_contatti->getIndirizzoSpedizione($contatti->getEmail());
            
            
            
            _e('  
                <tr>
                    <td>'.$cliente->getNominativo().'</td>
                    <td>'.$cliente->getEmail().'</td>
                    <td style="text-align:left; padding-left:10px">'.$ind_sped['Via'].', '.$ind_sped['Civico'].'<br>'.$ind_sped['CAP'].' - '.$ind_sped['Citta'].'<br>('.$ind_sped['Prov'].')</td>
                    <td> '.$v_contatti->getTelefono($cliente->getEmail()).'</td>
                    <td> '.$v_contatti->getCellulare($cliente->getEmail()).'</td>
                    <td>
                        <form action="'.curPageURL().'&email='.urlencode($cliente->getEmail()).'" method="post">
                            <input style="width:50px; text-align:right" type="text" name="sconto" value="'.$cliente->getSconto().'">% 
                            <input class="aggiungi" type="submit" name="aggiorna-sconto" value="Aggiorna">
                        </form>
                    </td>
                </tr>
            ');
            
            $i++;
        }
        
        
        _e('</table>');
    }
    else{
        //non ho clienti
        _e('Nessun cliente registrato.');
    }
    

?>