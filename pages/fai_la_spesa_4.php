<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

if(!isset($_SESSION['fdv_riepilogo'])){
    $_SESSION['fdv_riepilogo'] = array();
}
 
//La pagina contiene altri prodotti che possono essere aggiunti all'ordine
    global $wpdb;  
    $path = plugins_url().'/gestione_ordine/images/prodotti/';
    
    //visualizziamo il carrello
    
    //CREO IL CARRELLO
    $carrello = new Carrello();
    

 //ok è la variabile che mi indica se sto usando correttamente la mail di un utente loggato o non loggato
 $ok = false;

 if(get_current_user_id()!= 0){
     //utente loggato
     global $current_user;
     get_currentuserinfo();
     $cliente = new Cliente(null, $current_user->user_email);
     $v_cliente = new ViewCliente($cliente);    
     $c_cliente = new ControllerCliente($cliente);
     
     $_SESSION['fdv_riepilogo']['nominativo'] = $v_cliente->getNominativo($cliente->getEmail());
     $_SESSION['fdv_riepilogo']['email'] = $current_user->user_email;
     
     $contatti = new Contatti($current_user->user_email);
     $v_contatti = new ViewContatti($contatti);
     
     $_SESSION['fdv_riepilogo']['telefono'] = $v_contatti->getTelefono($contatti->getEmail());
     $_SESSION['fdv_riepilogo']['cellulare'] = $v_contatti->getCellulare($contatti->getEmail());
     $_SESSION['fdv_riepilogo']['indirizzo'] = $v_contatti->getIndirizzoSpedizione($contatti->getEmail());
     
     $_SESSION['fdv_riepilogo']['note'] = strip_tags((isset($_POST['note'])) ? trim($_POST['note']) : '');
     
     $ok = true;
     
 }
 else{
     //utente non loggato
     
     //ottengo tutti i valori passati
     if(isset($_POST['conferma-ordine'])){
        
        $_SESSION['fdv_riepilogo']['email'] = strip_tags((isset($_POST['email'])) ? trim($_POST['email']) : '');
         
        //devo fare un controllo se l'email utilizzata appartiene ad un utente loggato
        //se il controllo restituisce valore positivo, devo reindirizzare la navigazione alla pagina di login
        //altrimenti proseguo

        //preparo la query
        $query = "SELECT ID FROM wp_users WHERE user_email = '".$_SESSION['fdv_riepilogo']['email']."'";
        if($wpdb->get_results($query) != NULL){
            //ho ottenuto un risultato
            //la mail utilizzata appartiene ad un utente registrato
            //re-indirizzo alla pagina di login
            
            _e('<p>La mail utilizzata appartiene già ad un utente di Frutti da Favola<p>');
            _e('<p>Per continuare si prega di effettuare il login</p>');
            _e('<div style="width:50%">');
            
            $args = array(
                'echo'           => true,
                'redirect'       => curPageURL(), 
                'form_id'        => 'loginform',
                'label_username' => __( 'Username' ),
                'label_password' => __( 'Password' ),
                'label_remember' => __( 'Remember Me' ),
                'label_log_in'   => __( 'Log In' ),
                'id_username'    => 'user_login',
                'id_password'    => 'user_pass',
                'id_remember'    => 'rememberme',
                'id_submit'      => 'wp-submit',
                'remember'       => true,
                'value_username' => NULL,
                'value_remember' => false
            );
            
            wp_login_form($args);
            
            _e('</div>');
            
        }
        else{
            
            $_SESSION['fdv_riepilogo']['nome'] = strip_tags((isset($_POST['nome'])) ? trim($_POST['nome']) : '');
            $_SESSION['fdv_riepilogo']['cognome'] = strip_tags((isset($_POST['cognome'])) ? trim($_POST['cognome']) : '');         
            $_SESSION['fdv_riepilogo']['nominativo'] = $_SESSION['fdv_riepilogo']['nome'].' '. $_SESSION['fdv_riepilogo']['cognome'];

            $_SESSION['fdv_riepilogo']['telefono'] = strip_tags((isset($_POST['telefono'])) ? trim($_POST['telefono']) : '');
            $_SESSION['fdv_riepilogo']['cellulare'] = strip_tags((isset($_POST['cellulare'])) ? trim($_POST['cellulare']) : '');

            $indirizzo['Via'] = strip_tags((isset($_POST['indirizzo-via'])) ? trim($_POST['indirizzo-via']) : '');
            $indirizzo['Civico'] = strip_tags((isset($_POST['indirizzo-civico'])) ? trim($_POST['indirizzo-civico']) : '');
            $indirizzo['CAP'] = strip_tags((isset($_POST['indirizzo-cap'])) ? trim($_POST['indirizzo-cap']) : '');
            $indirizzo['Citta'] = strip_tags((isset($_POST['indirizzo-citta'])) ? trim($_POST['indirizzo-citta']) : '');
            $indirizzo['Prov'] = strip_tags((isset($_POST['indirizzo-provincia'])) ? trim($_POST['indirizzo-provincia']) : '');
            
            $_SESSION['fdv_riepilogo']['indirizzo'] = $indirizzo;
        
            $_SESSION['fdv_riepilogo']['note'] = strip_tags((isset($_POST['note'])) ? trim($_POST['note']) : '');        

            $ok = true;
        }
     }     
 }

 if($ok){
	//includo il carrello
 	include 'fai_la_spesa_4_carrello.php';
     //MOSTRA LA GUIDA ALGLI ACQUISTI
    _e('<div id="contenitore-guida">');
    _e('    <div id="step1" class="step active">');
    _e('     <h3>1</h3> <h4><a href="http://alexsoluzioniweb.it/fai-la-spesa/step-1/">SCEGLI LA CASSETTA</a></h4>');
    _e('     <p>Aggiungi all\'ordine una cassetta già fatta oppure componila con i prodotti che vuoi.</p>');
    _e('    </div>');
    _e('    <div class="middle-step"></div>');
    _e('    <div id="step2" class="step active">');
    _e('     <h3>2</h3><h4><a href="http://alexsoluzioniweb.it/fai-la-spesa/step-2/">AGGIUNGI PRODOTTI</a></h4>');
    _e('     <p>Puoi aggiungere ulteriori prodotti alla tua cassetta.</p>');
    _e('    </div>');
    _e('    <div class="middle-step"></div>');
    _e('    <div id="step3" class="step active">');
    _e('     <h3>3</h3><h4><a href="http://alexsoluzioniweb.it/fai-la-spesa/step-3/">DATI DI SPEDIZIONE</a></h4>');
    _e('     <p>Inserisci i tuoi dati, oppure accedi se sei già un nostro cliente.</p>');
    _e('    </div>');
    _e('    <div class="middle-step"></div>');
    _e('    <div id="step4" class="step active">');
    _e('     <h3>4</h3> <h4>CONFERMA ORDINE</h4>');
    _e('     <p>L\'ordine effettuato ti soddisfa? Inviacelo!</p>');
    _e('    </div>');
    _e('</div>');
    _e('<div style="clear:both; display:block"></div>');
 
 
 
    if(isset($totale_ordine)){
        $_SESSION['fdv_riepilogo']['costo_finale'] = $totale_ordine;
    }
    else{
        $_SESSION['fdv_riepilogo']['costo_finale'] = 0;
    }
 
 
    _e('<div id="visualizza-destinatario">');
    _e(    '<span style="font-size:1.8em">Info Spedizione</span><br><br>');
    _e(    '<div style="margin-left:20px">');
    _e(        '<span style="font-size:1.5em">'.$_SESSION['fdv_riepilogo']['nominativo'].'</span><br>');
    _e(        '<div style="float:left; width:33%; margin-top:15px">');
    _e(            '<table style="width:100%; margin-left:10px" cellpadding="5">');
    _e(                '<tr><td>Indirizzo:</td><td>'.$_SESSION['fdv_riepilogo']['indirizzo']['Via'].'</td></tr>'); 
    _e(                '<tr><td>Civico:</td><td>'.$_SESSION['fdv_riepilogo']['indirizzo']['Civico'].'</td></tr>');
    _e(                '<tr><td>CAP:</td><td>'.$_SESSION['fdv_riepilogo']['indirizzo']['CAP'].'</td></tr>');
    _e(                '<tr><td>Città:</td><td>'.$_SESSION['fdv_riepilogo']['indirizzo']['Citta'].'</td></tr>');
    _e(                '<tr><td>Provincia:</td><td>'.$_SESSION['fdv_riepilogo']['indirizzo']['Prov'].'</td></tr>');
    _e(            '</table>');
    _e(        '</div>');
    _e(        '<div style="float:left; width:33%; margin-top:15px">');
    _e(            '<table style="width:100%;" cellpadding="5">');
    _e(                '<tr><td>Telefono:</td><td>'.$_SESSION['fdv_riepilogo']['telefono'].'</td></tr>'); 
    _e(                '<tr><td>Cellulare:</td><td>'.$_SESSION['fdv_riepilogo']['cellulare'].'</td></tr>');
    _e(            '</table>');
    _e(        '</div>');
    _e(        '<div style="float:left; width:33%; margin-top:15px; font-size:1.4em">');
    _e(            'Note:<br>');
    _e(            '<p>'.$_SESSION['fdv_riepilogo']['note'].'</p>');
    _e(        '</div>');
    _e(        '<div style="float:none; clear:both"></div>');
    _e(    '</div>');
    _e('</div>');
 

    _e('<div style="width:100%; text-align:center; margin-top:30px"><form action="http://alexsoluzioniweb.it/progetti/fdv/fai-la-spesa/step-5" method="post"><input class="aggiungi" style="float:left" onClick="location.href=\'http://alexsoluzioniweb.it/progetti/fdv/fai-la-spesa/step-3/\'" type="button" value="<< INDIETRO"><input class="aggiungi" style="padding:25px" type="submit" value="CONFERMA ORDINE" name="aggiungi-nuovo-ordine" ></form></div>');
 

 }
 
 

?>