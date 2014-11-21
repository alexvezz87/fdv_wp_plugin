<?php

    //AUTORE: Alex Vezzelli - alexsoluzioniweb.it
    
    //Listener registrazione utente
    if(isset($_POST['registra-utente'])){
        //devo inserire i dati ricevuti in due tabelle
        //la prima è quella di crazione di nuovo utente
        //la seconda è quella relativa al cliente
        global $wpdb;
        
        //controllo se i dati inseriti sono corretti
        
        $email1 = strip_tags((isset($_POST['email1'])) ? trim($_POST['email1']) : '');
        $email2 = strip_tags((isset($_POST['email2'])) ? trim($_POST['email2']) : '');
        
        $passwd1 = strip_tags((isset($_POST['passwd1'])) ? trim($_POST['passwd1']) : '');
        $passwd2 = strip_tags((isset($_POST['passwd2'])) ? trim($_POST['passwd2']) : '');
        
        $username = strip_tags((isset($_POST['username'])) ? trim($_POST['username']) : '');
        
        //controllo se i campi sono stati compilati e che siano corrispondenti
        if($username != '' && $email1 != '' && $email2 != '' && $passwd1 != '' && $passwd2 != '' && $email1 === $email2 && $passwd1 === $passwd2){
            //i campi sono stati compilati e corretti
            $email = $email1;
            $passwd = $passwd1;
            
            //proseguo nell'ottenere i valori restanti
            //per fare in modo di poter registrare un cliente/utente, tutti i campi devono essere compilati
            $nome = strip_tags((isset($_POST['nome'])) ? trim($_POST['nome']) : '');
            $cognome = strip_tags((isset($_POST['cognome'])) ? trim($_POST['cognome']) : '');
            $telefono = strip_tags((isset($_POST['telefono'])) ? trim($_POST['telefono']) : '');
            $cellulare = strip_tags((isset($_POST['cellulare'])) ? trim($_POST['cellulare']) : '');
            $indirizzo['Via'] = strip_tags((isset($_POST['indirizzo-via'])) ? trim($_POST['indirizzo-via']) : '');
            $indirizzo['Civico'] = strip_tags((isset($_POST['indirizzo-civico'])) ? trim($_POST['indirizzo-civico']) : '');
            $indirizzo['CAP'] = strip_tags((isset($_POST['indirizzo-cap'])) ? trim($_POST['indirizzo-cap']) : '');
            $indirizzo['Citta'] = strip_tags((isset($_POST['indirizzo-citta'])) ? trim($_POST['indirizzo-citta']) : '');
            $indirizzo['Prov'] = strip_tags((isset($_POST['indirizzo-provincia'])) ? trim($_POST['indirizzo-provincia']) : '');

            //controllo i campi obbligatori
            if($nome != '' && $cognome != '' && $indirizzo['Via'] != '' && $indirizzo['Civico'] != '' && $indirizzo['CAP'] != '' && $indirizzo['Citta'] != '' && $indirizzo['Prov'] != ''){
                //i campi obbligatori sono stati compilati
                //controllo se i campi telefono e cellulare sono compilati
                if($telefono != '' || $cellulare != ''){
                    //se almeno un campo è compilato, procedo
                    
                    try{
                        $errors = 0;
                        $err_msg = array();
                        //controllo se l'utente non esiste già
                        //ottengo un array di clienti
                        $clienti = get_users(array('role' => 'customer'));
                        //eseguo dei controlli per sapere se inserire o meno l'utente
                        $inserisci_utente = false;
                        if(count($clienti) > 0){
                            
                            //ho dei valori da controllare
                            $i=0;
                            $trovato = false;
                           
                            while($i < count($clienti)){
                                $wp_user = $clienti[$i];
                                
                                                         
                                //$wp_user->data->user_email è il valore che contiene l'email del cliente                               
                                if($username == $wp_user->data->user_login){
                                    //se lo username è già presenti nel database, allora ho un riscontro
                                    $trovato = true;
                                }
                                if($username == 'Alex' || $username == 'Bacco' || $username == 'Cristina'){
                                    //Nomi degli amministratori
                                    $trovato = true;
                                }
                                $i++;
                            }
                            if($trovato == false){
                                //se non ho trovato nessuna occorrenza allora posso inserirlo.
                                $inserisci_utente = true;
                            }
                        }
                        else{
                            //non ho dei valori, quindi posso inserire il nuovo utente
                            $inserisci_utente = true;
                        }
                                                    
                        
                        if($inserisci_utente == true){
                            //il cliente non è già registrato, quindi posso procedere alla registrazione
                            //Procedo con l'inserimento dell'Utente
                            wp_insert_user(array('user_login' => $username,
                                                 'user_pass' => $passwd,
                                                 'user_email' => $email,
                                                 'role' => 'customer',
                                                 'first_name' => $nome,
                                                 'last_name' => $cognome));
                            //Procedo con l'inserimento del Cliente
                            $cliente = new Cliente($username, $email);                            
                            $cliente->setDatiPrivato($cognome.' '.$nome, '');
                            
                            //eseguo un controllo se per caso l'indirizzo è già presente nella tabella Clienti
                            $v_cliente = new ViewCliente($cliente);
                            $c_cliente = new ControllerCliente($cliente);
                            
                            $is_cliente = $v_cliente->isCliente($email);
                            if($is_cliente != -1 && $is_cliente != null){
                                //ho un riscontro di username, quindi vado ad aggiornare i campi
                                if(!$c_cliente->setClientePrivato($email, $nome, $cognome, '')){
                                    $err_msg[$errors] = "Errore in setClientePrivato, riga 99, registrazione_utente.php";
                                    $errors++;
                                }
                            }
                            else{
                                //non ho alcun riscontro quindi aggiungo i campi
                                if(!$c_cliente->saveCliente()){
                                    $err_msg[$errors] = "Errore in saveCliente, riga 106, registrazione_utente.php";
                                    $errors++;
                                }
                            }                         
                            
                            
                            //Procedo con l'inserimento dei Contatti
                            $contatti = new Contatti($email);
                            //print_r($indirizzo);
                            $contatti->setIndirizzoSpedizione($indirizzo);
                            if($telefono != ''){
                                $contatti->setTelefono($telefono);
                            }
                            if($cellulare != ''){
                                $contatti->setCellulare($cellulare);
                            }
                            $c_contatti = new ControllerContatti($contatti);
                            //eseguo un controllo nel caso i contatti non fossero già presenti
                            $v_contatti = new ViewContatti($contatti);
                            $is_contatto = $v_contatti->isContatto($email);
                            if($is_contatto != -1 && $is_contatto != null){
                                //il contatto è presente quindi devo aggiornare
                                if(!$c_contatti->setIndirizzoSpedizione($email, $indirizzo)){
                                    $err_msg[$errors] = "Errore in setIndirizzoSpedizione, riga 128, registrazione_utente.php";
                                    $errors++;
                                }
                                if($telefono != ''){ 
                                    if(!$c_contatti->setTelefono($email, $telefono)){
                                        $err_msg[$errors] = "Errore in setTelefono, riga 133, registrazione_utente.php";
                                        $errors++;
                                    }                                    
                                }
                                if($cellulare != ''){ 
                                    if(!$c_contatti->setCellulare($email, $cellulare)){
                                        $err_msg[$errors] = "Errore in setCellulare, riga 139, registrazione_utente.php";
                                        $errors++;
                                    }                                   
                                }    
                            }
                            else{
                                //il contatto non è presente, quindi lo aggiungo
                                if(!$c_contatti->saveContatti()){
                                    $err_msg[$errors] = "Errore in saveContatti, riga 147, registrazione_utente.php";
                                    $errors++;
                                }
                            }
                            
                            //tutto bene
                            if($errors == 0){
                                _e('<div id="right_insert">Registrazione avvenuta correttamente.</div>');
                            }
                            else{
                                 _e('<div id="errors_field">');
                                 $k = 0;
                                 while($k < $errors){
                                     _e($err_msg[$k].'<br>');
                                     $k++;
                                 }                                 
                                 _e('</div>');
                            }
                        }
                        else{
                            //L'utente è già presente ERRORE!
                            _e('<div id="errors_field">Lo Username inserito è già associato ad un utente di Frutti da Favola.<br>Pregasi di controllare e ripetere la procedura di registrazione.</div>');
                        }
                    }
                    catch(Exception $e){
                        _e($e);                        
                    }
                    
                }
                else{
                    //se non ho i campi compilati non proseguo
                     _e('<div id="errors_field">Almeno un contatto telefonico deve essere compilato</div>');
                }
            }
            else{
                //non sono stati compilati i campi obbligatori 
                _e('<div id="errors_field">');
                if($nome == ''){ _e('Campo nome vuoto.<br>');}
                if($cognome == ''){ _e('Campo cognome vuoto.<br>');  }
                if($indirizzo['Via'] == ''){ _e('Campo Indirizzo: Via, vuoto.<br>'); }
                if($indirizzo['Civico'] == ''){ _e('Campo Indirizzo: Civico, vuoto.<br>'); }
                if($indirizzo['CAP'] == ''){ _e('Campo Indirizzo: CAP, vuoto.<br>'); }
                if($indirizzo['Citta'] == ''){ _e('Campo Indirizzo: Città, vuoto.<br>'); }
                if($indirizzo['Prov'] == ''){ _e('Campo Indirizzo: Provincia, vuoto.<br>'); }            
                _e('</div>');
            }
       }        
       else{
            //i campi non sono corretti           
            _e('<div id="errors_field">');
            if($username == ''){ _e('Campo username vuoto.<br>');}
            if($email1 == ''){ _e('Primo campo email vuoto.<br>');  }
            if($email2 == ''){ _e('Secondo campo email vuoto.<br>'); }
            if($passwd1 == ''){ _e('Primo campo password vuoto.<br>'); }
            if($passwd2 == ''){ _e('Secondo campo password vuoto.<br>'); }
            if($email1 != $email2){ _e('I campi email sono diversi tra loro.<br>'); }
            if($passwd1 != $passwd2){ _e('I campi password sono diversi tra loro.<br>'); }            
            _e('</div>');
        }        
        
    }
    
    //Pagina di registrazione utente
    _e('
      <div id="container-form">
       
        <form action="'.curPageURL().'" method="post">
            <div>
                <legend><h3>Dati Utente</h3></legend>
                <table class="table-form">
                    <tr>
                        <td><label for="username">Username</label></td>
                        <td colspan="2"><input style="height:30px" type="text" value="" name="username" required ></td>
                    </tr>
                    <tr>
                        <td><label for="email">Email</label></td>
                        <td><input style="height:30px" type="email" value="" name="email1" required></td>
                        <td><input style="height:30px" type="email" value="" name="email2" placeholder="conferma email" required></td>
                    </tr>
                    <tr>
                        <td><label for="password">Password</label></td>
                        <td><input style="height:30px" type="password" value="" name="passwd1" required></td>
                        <td><input style="height:30px" type="password" value="" name="passwd2" placeholder="conferma password" required></td>
                    </tr>
                </table>
            </div>
            <div>
                
                <table class="table-form">
                    <tr>
                        <td><label for="nome">Nome</label></td>
                        <td><input style="height:30px" type="text" value="" name="nome" required ></td>
                    </tr>
                    <tr>
                        <td><label for="cognome">Cognome</label></td>
                        <td><input style="height:30px" type="text" value="" name="cognome" required ></td>
                    </tr>
                    <tr>
                        <td><label for="telefono">Telefono</label></td>
                        <td><input style="height:30px" type="text" value="" name="telefono" ></td>
                    </tr>
                    <tr>
                        <td><label for="cellulare">Cellulare</label></td>
                        <td><input style="height:30px" type="text" value="" name="cellulare" ></td>
                    </tr>
               </table>
            </div>
            <div>
                <table class="table-form-indirizzo">
                    <tr>
                        <td style="width:160px"><label for="via">Via/Piazza</label></td>
                        <td ><input style="width:300px; height:30px" type="text" value="" name="indirizzo-via" required>
                        <td style=" width:40px" ><label for="civico">N°</label></td>
                        <td><input style="height:30px; width:40px" type="text" value="" name="indirizzo-civico" size="5" required></td>
                    </tr>
                    <tr>
                        <td><label for="citta">Citt&agrave;</label></td>
                        <td colspan="3"><input style="width:300px; height:30px" type="text" name="indirizzo-citta" value="" required ></td>
                    </tr>
                    <tr>
                        <td><label for="cap">CAP</label></td>
                        <td><input style="height:30px; width:100px" type="text" value="" name="indirizzo-cap" required ></td>
                        <td><label for="provincia">Prov</label></td>
                        <td><input style="height:30px; width:40px" type="text" value="" name="indirizzo-provincia" size="5" maxlength="2" required ></td>
                    </tr>
                </table>
            </div>
            <div style="float:none; clear:both">
                <input onclick="abilita(this)" type="checkbox" name="privacy" value="1"> Accetto la normativa sulla <a href="'.curPageURL().'#">privacy</a><br><br>
                <input style="height:30px" type="submit" id="registra-utente" name="registra-utente" value="REGISTRAMI" disabled>
                    
            </div>
        </form>
      </div>
    ');
    
?>
