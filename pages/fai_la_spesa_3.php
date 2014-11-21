<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/


    //controllo se l'utente è loggato o meno
   
    if(get_current_user_id()!= 0){
        //ottengo la mail
        global $current_user;
        get_currentuserinfo();
        //form con i dati di spedizione
        $cliente = new Cliente(null, $current_user->user_email);
        $v_cliente = new ViewCliente($cliente);    
        $c_cliente = new ControllerCliente($cliente);
        
        _e('<span style="font-size:2em">'.$v_cliente->getNominativo($current_user->user_email).'</span>');
        
        $contatti = new Contatti($current_user->user_email);
        $v_contatti = new ViewContatti($contatti);
        $indirizzo = $v_contatti->getIndirizzoSpedizione($current_user->user_email);
        _e('<br><br>');
        _e('<span style="font-size:1.5em">Indirizzo Spedizione</span><br>');
        _e('<table style="font-size:1.2em; width:400px; margin-left:20px; margin-top:10px">');
        _e('    <tr>');
        _e('        <td style="width:100px">Via</td><td>'.$indirizzo['Via'].' n.'.$indirizzo['Civico'].'</td>');
        _e('    </tr>');
        _e('    <tr>');
        _e('        <td>CAP</td><td>'.$indirizzo['CAP'].'</td>');
        _e('    </tr>');
        _e('    <tr>');
        _e('        <td>Città</td><td>'.$indirizzo['Citta'].'</td>');
        _e('    </tr>');
        _e('    <tr>');
        _e('        <td>Provincia</td><td>'.$indirizzo['Prov'].'</td>');
        _e('    </tr>');
        _e('</table>');
        
        $telefono = $v_contatti->getTelefono($current_user->user_email);
        if($telefono == null || $telefono == '' ){
            $telefono = '-';
        }
        $cellulare = $v_contatti->getCellulare($current_user->user_email);
        if($cellulare == null || $cellulare == ''){
            $cellulare = '-';
        }
        
        _e('<br><span style="font-size:1.5em">Contatti</span><br>');
        _e('<table style="font-size:1.2em; width:400px; margin-left:20px; margin-top:10px">');
        _e('    <tr>');
        _e('        <td style="width:100px">Telefono</td><td>'.$telefono.'</td>');
        _e('    </tr>');
        _e('    <tr>');
        _e('        <td>Cellulare</td><td>'.$cellulare.'</td>');
        _e('    </tr>');       
        _e('</table>');
        
        _e('<form action="http://alexsoluzioniweb.it/progetti/fdv/fai-la-spesa/step-4/" method="post">');
        
        _e('<div style="margin-left:5px; margin-top:20px">
                <label for="note">Note</label>
                <textarea style="width:500px" name="note" cols="50" rows="8"></textarea>
            </div>');
        _e('<input class="aggiungi" style="float:left" onClick="location.href=\'http://alexsoluzioniweb.it/progetti/fdv/fai-la-spesa/step-2/\'" type="button" value="<< INDIETRO">'
                . '<input class="aggiungi" style="float:right" type="submit" value="CONTINUA >>" name="conferma-ordine">');
        _e('</form>');
        
        
    }
    else{
        
        //form per ottenere i dati di spedizione
        
        //istanzio le variabili di sessione
        $nome = (isset($_SESSION['fdv_riepilogo'])) ? $_SESSION['fdv_riepilogo']['nome'] : '';
        $cognome = (isset($_SESSION['fdv_riepilogo'])) ? $_SESSION['fdv_riepilogo']['cognome'] : '';
        $email = (isset($_SESSION['fdv_riepilogo'])) ? $_SESSION['fdv_riepilogo']['email'] : '';
        $telefono = (isset($_SESSION['fdv_riepilogo'])) ? $_SESSION['fdv_riepilogo']['telefono'] : '';
        $cellulare = (isset($_SESSION['fdv_riepilogo'])) ? $_SESSION['fdv_riepilogo']['cellulare'] : '';
        $via = (isset($_SESSION['fdv_riepilogo'])) ? $_SESSION['fdv_riepilogo']['indirizzo']['Via'] : '';
        $civico = (isset($_SESSION['fdv_riepilogo'])) ? $_SESSION['fdv_riepilogo']['indirizzo']['Civico'] : '';
        $citta = (isset($_SESSION['fdv_riepilogo'])) ? $_SESSION['fdv_riepilogo']['indirizzo']['Citta'] : '';
        $cap = (isset($_SESSION['fdv_riepilogo'])) ? $_SESSION['fdv_riepilogo']['indirizzo']['CAP'] : '';
        $prov = (isset($_SESSION['fdv_riepilogo'])) ? $_SESSION['fdv_riepilogo']['indirizzo']['Prov'] : '';
        $note = (isset($_SESSION['fdv_riepilogo'])) ? $_SESSION['fdv_riepilogo']['note'] : '';
        
        _e('
      <div id="container-form">
       
        <form action="http://alexsoluzioniweb.it/progetti/fdv/fai-la-spesa/step-4/" method="post">
            
            <div>
              
                <table class="table-form">
                    <tr>
                        <td><label for="nome">Nome</label></td>
                        <td><input type="text" value="'.$nome.'" name="nome" required ></td>
                    </tr>
                    <tr>
                        <td><label for="cognome">Cognome</label></td>
                        <td><input type="text" value="'.$cognome.'" name="cognome" required ></td>
                    </tr>
                    <tr>
                        <td><label for="email">Email</labrel></td>
                        <td><input type="email" value="'.$email.'" name="email" required ></td>
                    </tr>
                    <tr>
                        <td><label for="telefono">Telefono</label></td>
                        <td><input type="text" value="'.$telefono.'" name="telefono" ></td>
                    </tr>
                    <tr>
                        <td><label for="cellulare">Cellulare</label></td>
                        <td><input type="text" value="'.$cellulare.'" name="cellulare" ></td>
                    </tr>
               </table>
            </div>
            <div>
                <table class="table-form-indirizzo">
                    <tr>
                        <td style="width:160px"><label for="via">Via/Piazza</label></td>
                        <td ><input id="ind-via" type="text" value="'.$via.'" name="indirizzo-via" required>
                        <td style=" width:40px" ><label for="civico">N°</label></td>
                        <td><input style="width:40px" type="text" value="'.$civico.'" name="indirizzo-civico" size="5" required></td>
                    </tr>
                    <tr>
                        <td><label for="citta">Citt&agrave;</label></td>
                        <td colspan="3"><input style="width:300px;" type="text" name="indirizzo-citta" value="'.$citta.'" required ></td>
                    </tr>
                    <tr>
                        <td><label for="cap">CAP</label></td>
                        <td><input style="width:100px" type="text" value="'.$cap.'" name="indirizzo-cap" required ></td>
                        <td><label for="provincia">Prov</label></td>
                        <td><input style="width:40px" type="text" value="'.$prov.'" name="indirizzo-provincia" size="5" maxlength="2" required ></td>
                    </tr>
                </table>
            </div>
            <div style="margin-left:5px">
                <label for="note">Note</label>
                <textarea style="width:500px" name="note" cols="50" rows="8">'.$note.'</textarea>
            </div>
            <div style="float:none; clear:both">
                <input type="checkbox" name="privacy" value="1" required> Accetto la normativa sulla <a href="'.curPageURL().'#">privacy</a><br><br>
                
            </div>
            
                <input class="aggiungi" style="float:left" onClick="location.href=\'http://alexsoluzioniweb.it/progetti/fdv/fai-la-spesa/step-2/\'" type="button" value="<< INDIETRO">
                <input class="aggiungi" style="float:right" type="submit" value="CONTINUA >>" name="conferma-ordine">
            
        </form>
      </div>
    ');
    }
    

?>