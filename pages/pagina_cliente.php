<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/
    
    //ottengo la mail
    global $current_user;
    get_currentuserinfo();
    
    //print_r($current_user);
    //Ascoltatore
    if(isset($_POST['aggiorna-indirizzo'])){
        //prendo i valori 
        $sped['Via'] = strip_tags((isset($_POST['spedizione-via'])) ? trim($_POST['spedizione-via']) : '');
        $sped['Civico'] = strip_tags((isset($_POST['spedizione-civ'])) ? trim($_POST['spedizione-civ']) : '');
        $sped['CAP'] = strip_tags((isset($_POST['spedizione-cap'])) ? trim($_POST['spedizione-cap']) : '');
        $sped['Citta'] = strip_tags((isset($_POST['spedizione-cit'])) ? trim($_POST['spedizione-cit']) : '');
        $sped['Prov'] = strip_tags((isset($_POST['spedizione-prv'])) ? trim($_POST['spedizione-prv']) : '');
        
        //aggiorno il tutto
        $contatti = new Contatti($current_user->user_email);
        //$contatti->setIndirizzoSpedizione($sped_via, $sped_civ, $sped_cap, $sped_cit, $sped_prv);
        $c_contatti = new ControllerContatti($contatti);
        if(!$c_contatti->setIndirizzoSpedizione($current_user->user_email, $sped)){
            //errore
            $errore = true;
           
        }
        else{
            //buon fine
            $errore = false;
            
        }
    }    
        
    //NB. Tutte le volte che invoco questa pagina, devo controllare l'email e nominativo, se è diversa aggiorno il file cliente
    
    $cliente = new Cliente(null, $current_user->user_email);
    $v_cliente = new ViewCliente($cliente);    
    $c_cliente = new ControllerCliente($cliente);
    
    $contatti_fuori = new Contatti($current_user->user_email);
    $v_contatti = new ViewContatti($contatti_fuori);
    $c_contatti_fuori = new ControllerContatti($contatti_fuori);
   
    
    if($v_cliente->getEmail($current_user->user_login) != $current_user->user_email){
        //Le mail sono diverse, quindi probabilmente è stato aggiornato il campo email
        //Di conseguenza setto la mail nuova nella tabella cliente
        try{
            
            //cambio prima la mail in contatti
            $c_contatti_fuori->setEmail($v_contatti->isContatto($v_cliente->getEmail($current_user->user_login)), $current_user->user_email);
            $c_cliente->setEmail($current_user->user_login, $current_user->user_email);
                        
        }
        catch (Exception $ex) {
            _e($ex);
        }
    }
    
    if($v_cliente->getNominativo($current_user->user_email) != $current_user->display_name){
        
        try{
            $c_cliente->setNominativo($current_user->user_email, $current_user->display_name);
        }
        catch(Exception $e){
            _e($e);
        }
    }
       
    $cliente = $v_cliente->getCliente($current_user->user_login);
    $contatti_fuori = $v_contatti->getContatti($current_user->user_email);
    
    //print_r($indirizzo_spedizione);
    //print_r($contatti);
    
    //print_r($cliente);
    
    _e('
        <h2>Riepilogo Dati Cliente: '.$current_user->display_name.'</h2>
        <table style="width:500px">
            <tr>
                <td>Email</td>
                <td style="font-size:0.9em">'.$current_user->user_email.'</td>
            </tr>
        </table>
       
        <h2>Indirizzo Spedizione</h2>');
        
        if(isset($errore)){
            if($errore == true){
                 _e('<div id="errors_field" style="color:red">Sono subentrati errori nell\'aggiornare l\'indirizzo.</div>');
            }
            else{
                _e('<div id="right_insert" style="color:green">Aggiornamento dati indirizzo, avvenuto con successo.</div>');
            }
        }
        
    
    $indirizzo_spedizione = $contatti_fuori->getIndirizzoSpedizione();
    
        
      _e(' <form action="'.curPageURL().'" method="post">
            <table  style="width:500px; margin-left:10px">  
                <tr>
                    <td>Via / Piazza</td>
                    <td style="font-size:0.9em"><input type="text" name="spedizione-via" value="'.$indirizzo_spedizione['Via'].'" style="width:300px"></td>
                </tr>
                <tr>
                    <td>Civico</td>
                    <td style="font-size:0.9em"><input type="text" name="spedizione-civ" value="'.$indirizzo_spedizione['Civico'].'" style="width:60px"></td>
                </tr>
                <tr>
                    <td>CAP</td>
                    <td style="font-size:0.9em"><input type="text" name="spedizione-cap" value="'.$indirizzo_spedizione['CAP'].'" style="width:100px"></td>
                </tr>
                <tr>
                    <td>Città</td>
                    <td style="font-size:0.9em"><input type="text" name="spedizione-cit" value="'.$indirizzo_spedizione['Citta'].'" style="width:300px"></td>
                </tr>
                <tr>
                    <td>Provincia</td>
                    <td style="font-size:0.9em"><input type="text" name="spedizione-prv" maxlength="2" value="'.$indirizzo_spedizione['Prov'].'" style="width:60px"></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" name="aggiorna-indirizzo" class="aggiungi" value="Modifica indirizzo"></td>
                </tr>
            </table>
        </form>
        
        <h2>Contatti telefonici</h2>');
        
        //Ascoltatore
        if(isset($_POST['aggiorna-telefono'])){
        //prendo i valori 
        $telefono = strip_tags((isset($_POST['telefono'])) ? trim($_POST['telefono']) : '');
        $cellulare = strip_tags((isset($_POST['cellulare'])) ? trim($_POST['cellulare']) : '');
        $fax = strip_tags((isset($_POST['fax'])) ? trim($_POST['fax']) : '');
        
        
        //aggiorno il tutto
        $contatti = new Contatti($current_user->user_email);
        //$contatti->setIndirizzoSpedizione($sped_via, $sped_civ, $sped_cap, $sped_cit, $sped_prv);
        $c_contatti = new ControllerContatti($contatti);
        if($c_contatti->setCellulare($current_user->user_email, $cellulare) && $c_contatti->setTelefono($current_user->user_email, $telefono) && $c_contatti->setFax($current_user->user_email, $fax)){
             //buon fine
            _e('<div id="right_insert" style="color:green">Aggiornamento contatti telefonici, avvenuto con successo.</div>');
        }
        else{
           
            //errore
            _e('<div id="errors_field" style="color:red">Sono subentrati errori nell\'aggiornare i contatti telefonici.</div>');
        }
    } 
      
       _e(' <form action="'.curPageURL().'" method="post">
            <table  style="width:500px; margin-left:10px">  
                <tr>
                    <td>Telefono</td>
                    <td style="font-size:0.9em"><input type="text" name="telefono" value="'.$v_contatti->getTelefono($current_user->user_email).'" style="width:300px"></td>
                </tr>
                <tr>
                    <td>Cellulare</td>
                    <td style="font-size:0.9em"><input type="text" name="cellulare" value="'.$v_contatti->getCellulare($current_user->user_email).'" style="width:300px"></td>
                </tr>
                <tr>
                    <td>Fax</td>
                    <td style="font-size:0.9em"><input type="text" name="fax" value="'.$v_contatti->getFax($current_user->user_email).'" style="width:300px"></td>
                </tr>
                
                <tr>
                    <td colspan="2"><input type="submit" name="aggiorna-telefono" class="aggiungi" value="Aggiorna contatti"></td>
                </tr>
            </table>
        </form>

        
    ');
    
?>